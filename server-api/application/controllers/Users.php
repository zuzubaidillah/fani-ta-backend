<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Users extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model('M_users');
	}

	public function index()
	{
		// pengambilan query setring
		$batas = isset($_GET['batas']) ? (int)$_GET['batas'] : 20;
		$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;

		$id_user = $this->input->post('auth_key');
		// cek autorization
		$cek = $this->M_users->cek_id($id_user);
		if ($cek) {
			// cek jumlah data
			$cek = $this->M_users->cek_data();

			// $cek 1 / 0
			if ($cek) {
				// ternary, Untuk mengetahui hasil baris_awal yang akan ditampilkan
				$baris_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

				// mengirim baris awal dan batas ke models
				$get = $this->M_users->get_all($baris_awal, $batas);

				$jml = $get['jumlah'];
				// membulatkan keatas 0.7 menjadi 1 | 1.2 menjadi 2
				$totalPage = ceil($jml / $batas);

				// array multidimensi: array didalam array
				$hasil = [
						"data" => $get['data'],
						"meta" => [
								"halaman" => $halaman,
								"batas" => $batas,
								"totalData" => (int)$get['jumlah'],
								"totalHalaman" => $totalPage,
						],
						"code" => 200,
						"status" => "sukses",
						"message" => 'Data ditemukan'
				];
			}else{
				$hasil = [
						"code" => 200,
						"status" => 'error',
						"message" => 'Data Kosong',
						'meta' => null,
						'data' => null,
				];
			}
		}else{
			$hasil = [
					"code" => 200,
					"status" => 'error',
					"message" => 'Autorization Salah',
					'meta' => null,
					'data' => null,
			];
		}


		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}

	public function cek_data()
	{

		// cek data
		$cek = $this->M_users->cek_data();
		// 1 / 0
		if ($cek) {
			// jika nama user tidak ditemukan
			$hasil = [
				'code' => 200,
				'status' => 'sukses',
				'message' => 'Data Ditemukan',
				'meta' => 'ada',
				'data' => 'ada',
			];
		}else{
			// jika nama user tidak ditemukan
			$hasil = [
				'code' => 200,
				'status' => 'error',
				'message' => 'Data Kosong',
				'meta' => null,
				'data' => null,
			];
		}

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}

	public function register()
	{
		// mmbuat variabel untuk post data yang dikirim
		$nama = $this->input->post('nama');
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$nomor = $this->input->post('nomor');
		
		// cek apakah nama user sudah digunakan
		$cek = $this->M_users->cek_username($username);

		if ($cek) {
			$hasil = [
				'code' => 200,
				'status' => 'error',
				'message' => 'Username sudah digunakan, Silahkan masukan katakunci yang lain!',
				'meta' => null,
				'data' => null,
			];
		}else{
			// mengelompokan menjadi 1 di array dengan menambahkaan kata kunci sesuai kolom pada tabel
			$id_user = mt_rand(1000000000,2147483647);
			$cek = $this->M_users->cek_id($id_user);
			if ($cek) {
				$id_user = mt_rand(1,999999999);
			}

			$data_daftar = [
				'id_user' => $id_user,
				'nama' => $nama,
				'username' => $username,
				'nomor_wa' => $nomor,
				'password' => password_hash($password, PASSWORD_DEFAULT)
			];
			
			$getTabel = $this->M_users->post_user($data_daftar);

			// 1 / 0
			if ($getTabel) {
				$hasil = [
					'code' => 201,
					'status' => 'sukses',
					'message' => 'Data Berhasil Disimpan. Username: '.$username,
					'meta' => null,
					'data' => null,
				];
			} else {
				$hasil = [
					'code' => 200,
					'status' => 'error',
					'message' => 'Data Gagal Disimpan',
					'meta' => null,
					'data' => null,
				];
			}
		}

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}

	public function login()
	{
		// mmbuat variabel untuk post data yang dikirim
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// cek apakah nama user ada ditable
		$cek_username = $this->M_users->cek_username($username);

		if ($cek_username) { 
			// jika nam user ada di tabel

			// get password
			$get_password = $this->M_users->get_password($username);

			// proses cek password
			if (password_verify($password, $get_password->password)) {

				// data di table dikirim ke frontend untuk dijadikan nilai session
				$data = [
					'id' => $get_password->id_user,
					'nama' => $get_password->nama
				];

				// data melalui array
				$hasil = [
					'code' => 200,
					'status' => 'sukses',
					'message' => "Selamat Datang $username, Di Aplikasi Pemetaan Jambu Darsono",
					'data' => $data,
					'meta' => 'ada',
				];
			}else{
				$hasil = [
					'code' => 200,
					'status' => 'error',
					'message' => 'Password tidak ditemukan',
					'meta' => null,
					'data' => null,
				];
			}


		}else{
			// jika nama user tidak ditemukan
			$hasil = [
				'code' => 200,
				'status' => 'error',
				'message' => 'Nama User tidak ditemukan',
				'meta' => null,
				'data' => null,
			];
		}

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}
}
