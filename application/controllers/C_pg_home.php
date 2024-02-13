<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_pg_home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	
	
	public function index()
	{
		//$this->load->view('welcome_message');
		
		$gbl_kode_kantor = 'TK1';
		
		$data = array('gbl_kode_kantor'=>$gbl_kode_kantor);
		$this->load->view('public/container.html',$data);
	}
	
	public function detail_halaman()
	{
		$gbl_kode_kantor = 'TK1';
		
		//$link = $this->uri->segment(2,0);
		$slug = $this->uri->segment(2,0);
		if((!empty($this->uri->segment(2,0))) && ($this->uri->segment(2,0)!= "")  )
		{
			$get_halaman = "
							SELECT 
								A.*
								,COALESCE(B.img_file,'') AS img_file
								,COALESCE(B.img_url,'') AS img_url
								,COALESCE(B.ext_file,'') AS ext_file
								,COALESCE(B.base_url,'') AS base_url
							FROM tb_menu_website AS A
							LEFT JOIN 
							(
								SELECT 
										kode_kantor,id,group_by
										,MAX(img_file) AS img_file
										,MAX(img_url) AS img_url
										,MAX(ext_file) AS ext_file
										,MAX(base_url) AS base_url
								FROM tb_images 
								WHERE kode_kantor = '".$gbl_kode_kantor."'
								GROUP BY kode_kantor,id,group_by
							)
							AS B ON A.kode_kantor = B.kode_kantor AND A.id_menu = B.id AND B.group_by = 'BANNERPAGE'
							WHERE A.kode_kantor = '".$gbl_kode_kantor."' 
							AND A.slug = '".$slug."' ; ";
			
			$data_halaman = $this->M_gl_pengaturan->view_query_general($get_halaman);
			if(!empty($data_halaman))
			{
				$data_halaman = $data_halaman->row();
				$data = array('data_halaman' => $data_halaman,'gbl_kode_kantor'=>$gbl_kode_kantor);
				$this->load->view('public/container.html',$data);
			}
			else
			{
				$list_amal_terbatas = $this->M_amal->list_amal_limit('',2,0);
				$data = array('list_amal_terbatas' => $list_amal_terbatas);
				$this->load->view('public/home/container_home.html',$data);
			}
		}
		else
		{
			header('Location: '.base_url());
		}
	}

	public function view_list_funding()
	{
		$gbl_kode_kantor = 'TK1';
		
		//GET HALAMAN
			$get_halaman = "
							SELECT 
								A.*
								,COALESCE(B.img_file,'') AS img_file
								,COALESCE(B.img_url,'') AS img_url
								,COALESCE(B.ext_file,'') AS ext_file
								,COALESCE(B.base_url,'') AS base_url
								
							FROM tb_menu_website AS A
							LEFT JOIN 
							(
								SELECT 
										kode_kantor,id,group_by
										,MAX(img_file) AS img_file
										,MAX(img_url) AS img_url
										,MAX(ext_file) AS ext_file
										,MAX(base_url) AS base_url
								FROM tb_images 
								WHERE kode_kantor = '".$gbl_kode_kantor."'
								GROUP BY kode_kantor,id,group_by
							)
							AS B ON A.kode_kantor = B.kode_kantor AND A.id_menu = B.id AND B.group_by = 'BANNERPAGE'
							WHERE A.kode_kantor = '".$gbl_kode_kantor."' 
							AND A.isJenis = 'FUNDING' ; ";
			
			$data_halaman = $this->M_gl_pengaturan->view_query_general($get_halaman);
			if(!empty($data_halaman))
			{
				$data_halaman = $data_halaman->row();
			}
			else
			{
				$data_halaman = false;
			}
		//GET HALAMAN
		
		
		if((!empty($_GET['dari'])) && ($_GET['dari']!= "")  )
		{
			$dari = $_GET['dari'];
			$sampai = $_GET['sampai'];
			$ins_qry_between = " AND A.tgl_artikel BETWEEN '".$dari."' AND '".$sampai."'";
		}
		else
		{
			$dari = date("Y-m-d");
			$sampai = date("Y-m-d");
			$ins_qry_between = "";
		}
		
		if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
		{
			$cari = str_replace("'","",$_GET['cari']) ;
		}
		else
		{
			$cari = "";
		}
		
		$list_funding = "
						SELECT 
							A.* 
							,DATEDIFF(A.tgl_selesai_penggalangan,DATE(NOW())) AS sisa_waktu
							
							,SUBSTRING_INDEX(A.wil_prov,'|',1) AS kode_prov
							,SUBSTRING_INDEX(A.wil_prov,'|',-1) AS nama_prov
							
							,SUBSTRING_INDEX(A.wil_kabkot,'|',1) AS kode_kabkot
							,SUBSTRING_INDEX(A.wil_kabkot,'|',-1) AS nama_kabkot
							
							,SUBSTRING_INDEX(A.wil_kec,'|',1) AS kode_kec
							,SUBSTRING_INDEX(A.wil_kec,'|',-1) AS nama_kec
							
							,SUBSTRING_INDEX(A.wil_des,'|',1) AS kode_des
							,SUBSTRING_INDEX(A.wil_des,'|',-1) AS nama_des
							
						FROM tb_artikel_frontend AS A
						WHERE A.kode_kantor = '".$gbl_kode_kantor."' 
						AND A.group_by = 'PENGGALANGAN' 
						".$ins_qry_between."
						AND (A.judul LIKE '%".$cari."%' OR A.sumber LIKE '%".$cari."%')
						ORDER BY A.tgl_ins DESC LIMIT 0,50";
		//echo $list_funding;
		$list_funding = $this->M_gl_pengaturan->view_query_general($list_funding);
		
		/*
		if(!empty($list_funding))
		{
			$list_result = $list_funding->result();
			foreach($list_result as $row)
			{
				echo $row->judul;
			}
		}
		*/
		
		$data = array('gbl_kode_kantor' => $gbl_kode_kantor,'page_content'=>'page_funding','list_funding' => $list_funding, 'data_halaman' => $data_halaman);
		$this->load->view('public/container.html',$data);
		
	}
}

