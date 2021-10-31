<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Lokasi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model('M_users');
		$this->load->model('M_lokasi');
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
			$cek = $this->M_lokasi->cek_data();

			// $cek 1 / 0
			if ($cek) {
				$baris_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

				// mengirim baris awal dan batas ke models
				$get = $this->M_lokasi->get_all($baris_awal, $batas);

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
					// membulatkan keatas 0.7 menjadi 1 | 1.2 menjadi 2
					$totalPage = ceil($jml / $batas);

					// {
					// 	"type": "FeatureCollection",
					// 	"features": [{
					// 			"type": "Feature",
					// 			"geometry": {
					// 				"type": "Point",
					// 												[lintang_latitude, bujur_longtitude]
					// 				"coordinates": [-77.034084142948, 38.909671288923]
					// 			},
					// 			"properties": {
					// 				"phoneFormatted": "(202) 234-7336",
					// 				"phone": "2022347336",
					// 				"address": "1471 P St NW",
					// 				"city": "Washington DC",
					// 				"country": "United States",
					// 				"crossStreet": "at 15th St NW",
					// 				"postalCode": "20005",
					// 				"state": "D.C."
					// 			}
					// 		}
					// 	]
					// }

					$d = [];
					$d_coordinate = [];
					foreach ($get['data'] as $key) {
						$d[] = [
							'type' => 'Feature',
							'geometry' => [
								"type" => 'Point',
								"coordinates" => [$key->bujur_longtitude, $key->lintang_latitude]
							],
							'properties' => [
								"nama_user" => $key->nama,
								"nama_desa" => "Ds. " . $key->nama_desa,
								"nama_dusun" => "Dsn. " . $key->nama_dusun,
								"nama_pemilik" => $key->nama_pemilik,
								"nama_buah" => $key->nama_buah,
								"nama_jenis_buah" => $key->nama_jenis_buah,
								"id_set_titik_lokasi" => $key->id_set_titik_lokasi,
								"country" => "Indonesia",
								"keterangan" => $key->keterangan
							]
						];
						$d_coordinate[] = [$key->bujur_longtitude, $key->lintang_latitude];
					}

					$data_titik_lokasi = [
						"type" => 'FeatureCollection',
						"features" => $d,
						"all_coordinates" => $d_coordinate,
					];

					// array multidimensi: array didalam array
					$hasil = [
						"data" => $data_titik_lokasi,
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
		// pengambilan query setring
		$batas = isset($_GET['batas']) ? (int)$_GET['batas'] : 50;
		$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
		$s = isset($_GET['s']) ? $_GET['s'] : '';

		$id_user = $this->input->post('auth_key');
		// cek autorization
		$cek = $this->M_users->cek_id($id_user);
		if ($cek) {
			// cek jumlah data
			$cek = $this->M_lokasi->cek_data();

			// $cek 1 / 0
			if ($cek) {
				$baris_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

				// mengirim baris awal dan batas ke models
				$get = $this->M_lokasi->get_cari($baris_awal, $batas, $s);
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
					// membulatkan keatas 0.7 menjadi 1 | 1.2 menjadi 2
					$totalPage = ceil($jml / $batas);

					$d = [];
					$d_coordinate = [];
					foreach ($get['data'] as $key) {
						$d[] = [
							'type' => 'Feature',
							'geometry' => [
								"type" => 'Point',
								"coordinates" => [$key->bujur_longtitude, $key->lintang_latitude]
							],
							'properties' => [
								"nama_user" => $key->nama,
								"nama_desa" => "Ds. " . $key->nama_desa,
								"nama_dusun" => "Dsn. " . $key->nama_dusun,
								"nama_pemilik" => $key->nama_pemilik,
								"nama_buah" => $key->nama_buah,
								"nama_jenis_buah" => $key->nama_jenis_buah,
								"id_set_titik_lokasi" => $key->id_set_titik_lokasi,
								"country" => "Indonesia",
								"keterangan" => $key->keterangan
							]
						];
						$d_coordinate[] = [$key->bujur_longtitude, $key->lintang_latitude];
					}

					$data_titik_lokasi = [
						"type" => 'FeatureCollection',
						"features" => $d,
						"all_coordinates" => $d_coordinate,
					];

					// array multidimensi: array didalam array
					$hasil = [
						"data" => $data_titik_lokasi,
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

	public function tambah()
	{
		// mmbuat variabel untuk post data yang dikirim
		$lintang_latitude = trim($this->input->post('tl_lat_lintang'));
		$bujur_longtitude = trim($this->input->post('tl_long_bujur'));
		$nama_buah = trim($this->input->post('tl_nama_buah'));
		$id_pemilik_tanah = trim($this->input->post('tl_kepemilikan_tanah_id'));
		$id_dusun = trim($this->input->post('tl_nama_dusun'));
		$id_jenis_buah = trim($this->input->post('tl_nama_jenis_buah'));
		$keterangan = trim($this->input->post('tl_keterangan'));
		$id_user = trim($this->input->post('id_user'));
		// echo json_encode($lintang_latitude,$bujur_longtitude,$nama_buah,$id_pemilik_tanah,$id_dusun,$id_jenis_buah,$keterangan,$id_user);die();

		// cek apakah sudah ada isinya (keamanan)
		if ( $lintang_latitude === null || $lintang_latitude == '' || $bujur_longtitude === null || $bujur_longtitude == '' || $nama_buah === null || $nama_buah == '' || $id_pemilik_tanah === null || $id_pemilik_tanah == '' || $id_dusun === null || $id_dusun == '' || $id_jenis_buah === null || $id_jenis_buah == '' || $keterangan === null || $keterangan == '' || $id_user === null || $id_user == '') {
			$hasil = [
				"code" => 200,
				"status" => 'error',
				"message" => 'Terdapat nilai NULL, Ulangi kembali'
			];
		} else {
			// cek autorization
			$cek = $this->M_users->cek_id($id_user);
			if ($cek) {
				// proses tambah data

				// pengelompokan data
				$data_tambah = [
					'id_set_titik_lokasi' => mt_rand(1, 2147483647),
					'lintang_latitude' => $lintang_latitude,
					'bujur_longtitude' => $bujur_longtitude,
					'nama_buah' => 'Jambu Darsono',
					'id_pemilik_tanah' => $id_pemilik_tanah,
					'id_dusun' => $id_dusun,
					'id_jenis_buah' => $id_jenis_buah,
					'keterangan' => $keterangan,
					'id_users' => $id_user
				];

				// proses tambah ke models
				$getTabel = $this->M_lokasi->tambah_data($data_tambah);

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

	public function id()
	{
		$id = $this->input->post('id');
		$id_user = $this->input->post('auth_key');
		// cek autorization
		$cek = $this->M_users->cek_id($id_user);
		if ($cek) {
			// cek jumlah data
			$cek = $this->M_lokasi->cek_data();

			// $cek 1 / 0
			if ($cek) {

				// mengirim baris awal dan batas ke models
				$get = $this->M_lokasi->where_id($id);

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

					// array multidimensi: array didalam array
					$hasil = [
						"data" => $get['data'],
						"meta" => [
							"halaman" => 1,
							"batas" => 1,
							"totalData" => (int)$get['jumlah'],
							"totalHalaman" => 1,
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
}
