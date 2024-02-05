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
}

