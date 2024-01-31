<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
// if ( ! function_exists('tanggal'))
// {
	function tanggal($var = '')
	{
	$tgl = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$pecah = explode("-", $var);
	return $pecah[2]." ".$tgl[$pecah[1] - 1]." ".$pecah[0];
	}
	
	
	function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
	
 
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}

	function send_wa_api($no_hp, $nama, $url_map)
	{
		$curl = curl_init();

		$secretKey = 'eBzrH4jnzZoXxWNpVnLy';

		//VALIDASI NOMOR HP
		// kadang ada penulisan no hp 0811 239 345
		$no_hp = str_replace(" ","",$no_hp);
		// kadang ada penulisan no hp (0274) 778787
		$no_hp = str_replace("(","",$no_hp);
		// kadang ada penulisan no hp (0274) 778787
		$no_hp = str_replace(")","",$no_hp);
		// kadang ada penulisan no hp 0811.239.345
		$no_hp = str_replace(".","",$no_hp);

		// cek apakah no hp mengandung karakter + dan 0-9
		if(!preg_match('/[^+0-9]/',trim($no_hp))){
		 // cek apakah no hp karakter 1-3 adalah +62
		 if(substr(trim($no_hp), 0, 3)=='62'){
		     $no_hp = trim($no_hp);
		 }
		 // cek apakah no hp karakter 1 adalah 0
		 elseif(substr(trim($no_hp), 0, 1)=='0'){
		     $no_hp = '62'.substr(trim($no_hp), 1);
		 }
		}

		$postdata = array(
				'nohp' => $no_hp,
				'pesan' => "Hai ".$nama.", kami ingin mendengar masukan Anda tentang Glafidsya Aesthetic Clinic. Silakan berikan ulasan di profil kami",
				'notifyurl' => '' 
		);

		$payload = json_encode($postdata);

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://apiglafidsya.waviro.com/api/sendwa",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $payload,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json; charset=utf-8",
		    "SecretKey:  ".$secretKey.""
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if(!$err)
		{
			print_r(json_decode($response));
		} else {
			print_r($err);
		}

		$curl = curl_init();

		
		$postdata = array(
				'nohp' => $no_hp,
				'pesan' => $url_map,
				'notifyurl' => '' 
		);

		$payload = json_encode($postdata);

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://apiglafidsya.waviro.com/api/sendwa",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $payload,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: application/json; charset=utf-8",
		    "SecretKey:  ".$secretKey.""
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if(!$err)
		{
			print_r(json_decode($response));
		} else {
			print_r($err);
		}
	}




//}

