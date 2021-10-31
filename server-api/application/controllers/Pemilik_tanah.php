<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Pemilik_tanah extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model('M_users');
		$this->load->model('M_pemilik_tanah');
	}

	public function all()
	{
		// pengambilan query setring
		$batas = isset($_GET['batas']) ? (int)$_GET['batas'] : 50;
		$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;

		$id_user = $this->input->post('auth_key');
		// cek autorization
		$cek = $this->M_users->cek_id($id_user);

		if ($cek) {
			// cek jumlah data
			$cek = $this->M_pemilik_tanah->cek_data();

			// $cek 1 / 0
			if ($cek) {
				$baris_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

				// mengirim baris awal dan batas ke models
				$get = $this->M_pemilik_tanah->get_all($baris_awal, $batas);

				$jml = $get['jumlah'];
				if ($jml == 0) {
					$hasil = [
						"code" => 200,
						"status" => 'error',
						"message" => 'Data Kosong',
						'meta' => null,
						'data' => null,
					];
				} else {
					$totalPage = ceil($jml / $batas);

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
				}
			} else {
				$hasil = [
					"code" => 200,
					"status" => 'error',
					"message" => 'Data Kosong',
					'meta' => null,
					'data' => null,
				];
			}
		} else {
			$hasil = [
				"code" => 200,
				"status" => 'auth_salah',
				"message" => 'Auth Salah',
				'meta' => null,
				'data' => null,
			];
		}

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}

	public function cari()
	{
		// mendapatkan query dari frontend
		$kata_kunci = $this->input->post('query');

		// mengirim kata kunci ke models untuk dicocokan dengan data pada table
		$get = $this->M_pemilik_tanah->cari_nama_pemilik($kata_kunci);

		// cek jumlah
		if ($get['jumlah'] > 0) {
			// array multidimensi: array didalam array
			$hasil = [
				"data" => $get['data'],
				"meta" => [
					"halaman" => 1,
					"batas" => 0,
					"totalData" => (int)$get['jumlah'],
					"totalHalaman" => 1,
				],
				"code" => 200,
				"status" => "sukses",
				"message" => 'Data Ditemukan'
			];
		} else {
			$hasil = [
				"code" => 200,
				"status" => 'tidak_ditemukan',
				"message" => 'data tidak ditemukan',
				"data" => null,
				"meta" => null
			];
		}
		// menampilkan hasil
		$this->output
			->set_content_type('application/json')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}

	public function tambah()
	{
		// mmbuat variabel untuk post data yang dikirim
		$nama_pemilik = trim($this->input->post('nama_pemilik'));
		$nomor_wa = trim($this->input->post('nomor_wa'));

		// cek apakah sudah ada isinya (keamanan)
		if ($nama_pemilik === null || $nama_pemilik == '' || $nomor_wa === null || $nomor_wa == '') {
			$hasil = [
				"code" => 200,
				"status" => 'error',
				"message" => 'NULL nama kota'
			];
		} else {

			$id_user = $this->input->post('auth_key');
			// cek autorization
			$cek = $this->M_users->cek_id($id_user);
	
			if ($cek) {
				// cek apakah sudah ada nama_pemilik
				$get = $this->M_pemilik_tanah->cari_nama_pemilik_samdengan($nama_pemilik);

				if ($get['jumlah'] > 0) {
					$hasil = [
						"code" => 200,
						"status" => "error",
						"message" => 'Data Sudah ada!'
					];
				} else {
					// proses tambah data

					// pengelompokan data
					$data_tambah = [
						'id_pemilik_tanah' => mt_rand(1, 2147483647),
						'nama_pemilik' => $nama_pemilik,
						'nomor_wa' => $nomor_wa
					];

					// proses tambah ke models
					$getTabel = $this->M_pemilik_tanah->tambah_data($data_tambah);

					if ($getTabel) {
						$hasil = [
							'code' => 201,
							'status' => 'sukses',
							'message' => 'Data Berhasil Disimpan.',
						];
					} else {
						$hasil = [
							'code' => 200,
							'status' => 'error',
							'message' => 'Gagal Disimpan!',
						];
					}
				}
			} else {
				$hasil = [
					"code" => 200,
					"status" => 'error',
					"message" => 'Auth Salah',
					'meta' => null,
					'data' => null,
				];
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}
}
