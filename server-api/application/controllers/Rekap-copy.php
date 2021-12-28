<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Rekap extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model('M_users');
	}

	public function index()
	{
//		// cek jumlah data
//		$cek = $this->M_users->cek_data();
//
//		// $cek 1 / 0
//		if ($cek) {
//			$id_user = $this->input->post('auth_key');
//			// mengirim baris awal dan batas ke models
//			$get = $this->M_users->get_all($id_user);
//
//			// array multidimensi: array didalam array
//			$hasil = [
//				"data" => $get['data'],
//				"meta" => null,
//				"code" => 200,
//				"status" => "sukses",
//				"message" => 'Data ditemukan'
//			];
//		} else {
//			$hasil = [
//				"code" => 200,
//				"status" => 'error',
//				"message" => 'Data Kosong',
//				'meta' => null,
//				'data' => null,
//			];
//		}

		$rekap = [
			[
				"nama_dusun" => "Dusun 1",
				"nama_desa" => "Desa 1",
				"id_dusun" => "1",
				"data_buah" => [
					["nama_jenis_buah" => "buah 1",
						"id_jenis_buah" => "1",
						"jumlah" => 9
					],
					["nama_jenis_buah" => "buah 2",
						"id_jenis_buah" => "2",
						"jumlah" => 2
					],
					["nama_jenis_buah" => "buah 3",
						"id_jenis_buah" => "3",
						"jumlah" => 3
					],
					["nama_jenis_buah" => "buah 4",
						"id_jenis_buah" => "4",
						"jumlah" => 0
					]
				]
			],
			[
				"nama_dusun" => "Dusun 2",
				"nama_desa" => "Desa 2",
				"id_dusun" => "2",
				"data_buah" => [
					[
						"nama_jenis_buah" => "buah 1",
						"id_jenis_buah" => "1",
						"jumlah" => 9
					],
					[
						"nama_jenis_buah" => "buah 2",
						"id_jenis_buah" => "2",
						"jumlah" => 0
					],
					[
						"nama_jenis_buah" => "buah 3",
						"id_jenis_buah" => "3",
						"jumlah" => 3
					],
					[
						"nama_jenis_buah" => "buah 4",
						"id_jenis_buah" => "4",
						"jumlah" => 5
					]
				]
			],
			[
			"nama_dusun" => "Dusun 3",
			"nama_desa" => "Desa 3",
			"id_dusun" => "3",
			"data_buah" => [
				[
					"nama_jenis_buah" => "buah 1",
					"id_jenis_buah" => "1",
					"jumlah" => 0
				],
				[
					"nama_jenis_buah" => "buah 2",
					"id_jenis_buah" => "2",
					"jumlah" => 0
				],
				[
					"nama_jenis_buah" => "buah 3",
					"id_jenis_buah" => "3",
					"jumlah" => 0
				],
				[
					"nama_jenis_buah" => "buah 4",
					"id_jenis_buah" => "4",
					"jumlah" => 0
				]
			]
		]
		];
		$hasil = [
			"data" => $rekap,
			"meta" => null,
			"code" => 200,
			"status" => "sukses",
			"message" => 'Data ditemukan'
		];
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
		} else {
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
		} else {
			// mengelompokan menjadi 1 di array dengan menambahkaan kata kunci sesuai kolom pada tabel
			$id_user = mt_rand(1000000000, 2147483647);
			$cek = $this->M_users->cek_id($id_user);
			if ($cek) {
				$id_user = mt_rand(1, 999999999);
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
					'message' => 'Data Berhasil Disimpan. Username: ' . $username,
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
			} else {
				$hasil = [
					'code' => 200,
					'status' => 'error',
					'message' => 'Password tidak ditemukan',
					'meta' => null,
					'data' => null,
				];
			}


		} else {
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

	public function tambah()
	{
		// mmbuat variabel untuk post data yang dikirim
		$nama = trim($this->input->post('nama'));
		$no_wa = trim($this->input->post('no_wa'));
		$username = trim($this->input->post('username'));
		$password = trim($this->input->post('password'));

		// cek apakah sudah ada isinya (keamanan)
		if ($nama === null || $nama == '' || $no_wa === null || $no_wa == '' || $username === null || $username == '' || $password === null || $password == '') {
			$hasil = [
				"code" => 200,
				"status" => 'error',
				"message" => 'nilai blm lengkap, ulangi lagi'
			];
		} else {

			$id_user = $this->input->post('auth_key');
			// cek autorization
			$cek = $this->M_users->cek_id($id_user);

			if ($cek) {
				// cek apakah sudah ada nama
				$get = $this->M_users->cari_nama_pengguna_samdengan($nama);

				if ($get['jumlah'] > 0) {
					$hasil = [
						"code" => 200,
						"status" => "error",
						"message" => 'Nama Pengguna Sudah ada!'
					];
				} else {
					// cek apakah sudah ada nama
					$get = $this->M_users->cek_username($username);

					if ($get > 0) {
						$hasil = [
							"code" => 200,
							"status" => "error",
							"message" => 'Username Sudah ada!'
						];
					} else {
						// proses tambah data

						$date_create = date('Y-m-d h:i:s');
						// pengelompokan data
						$data_tambah = [
							'id_user' => mt_rand(1, 2147483647),
							'nama' => $nama,
							'username' => $username,
							'password' => password_hash($password, PASSWORD_DEFAULT),
							'date_create' => $date_create,
							'date_update' => $date_create,
							'nomor_wa' => $no_wa,
						];

						// proses tambah ke models
						$getTabel = $this->M_users->post_user($data_tambah);

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


	public function edit()
	{
		$id_user = $this->input->post('auth_key');
		$id_user_edit = $this->input->post('id_user');

		// cek autorization
		$cek = $this->M_users->cek_id($id_user);

		if ($cek) {
			// cek apakah sudah ada nama
			$get = $this->M_users->cek_id($id_user_edit);

			if ($get == 0) {
				$hasil = [
					"code" => 200,
					"status" => "error",
					"message" => 'id tidak ditemukan'
				];
			} else {
				// proses tambah data

				// proses tambah ke models
				$data_user = $this->M_users->ambil_user($id_user_edit);

				$hasil = [
					'code' => 200,
					'status' => 'sukses',
					'data' => $data_user['data'],
					'message' => 'Data Ditemukan.',
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

		$this->output
			->set_content_type('application/json')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}

	public function edit_proses()
	{
		// mmbuat variabel untuk post data yang dikirim
		$nama = trim($this->input->post('nama'));
		$no_wa = trim($this->input->post('no_wa'));
		$username = trim($this->input->post('username'));
		$password = trim($this->input->post('password'));
		$id_user_edit = trim($this->input->post('id_user_edit'));

		// cek apakah sudah ada isinya (keamanan)
		if ($nama === null || $nama == '' || $no_wa === null || $no_wa == '' || $username === null || $username == '' || $password === null || $id_user_edit === null || $id_user_edit == '') {
			$hasil = [
				"code" => 200,
				"status" => 'error',
				"message" => 'nilai blm lengkap, ulangi lagi'
			];
		} else {

			$id_user = $this->input->post('auth_key');
			// cek autorization
			$cek = $this->M_users->cek_id($id_user);

			if ($cek) {
				// cek apakah sudah ada nama
				$get = $this->M_users->cari_nama_pengguna_samdengan($nama, $id_user_edit);

				if ($get['jumlah'] > 0) {
					$hasil = [
						"code" => 200,
						"status" => "error",
						"message" => 'Nama Pengguna Sudah ada!'
					];
				} else {
					// cek apakah sudah ada nama
					$get = $this->M_users->cek_username($username, $id_user_edit);

					if ($get > 0) {
						$hasil = [
							"code" => 200,
							"status" => "error",
							"message" => 'Username Sudah ada!'
						];
					} else {
						// proses tambah data

						// pengelompokan data
						$data_tambah = [
							'nama' => $nama,
							'username' => $username,
							'nomor_wa' => $no_wa,
						];
						if ($password !== '') {
							$data_tambah['password'] = password_hash($password, PASSWORD_DEFAULT);
						}

						// proses tambah ke models
						$getTabel = $this->M_users->update_user($data_tambah, $id_user_edit);

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

	public function hapus()
	{
		// mmbuat variabel untuk post data yang dikirim
		$id_user_edit = trim($this->input->post('id_user'));

		// cek apakah sudah ada isinya (keamanan)
		if ($id_user_edit === null || $id_user_edit == '') {
			$hasil = [
				"code" => 200,
				"status" => 'error',
				"message" => 'nilai blm lengkap, ulangi lagi'
			];
		} else {

			$id_user = $this->input->post('auth_key');
			// cek autorization
			$cek = $this->M_users->cek_id($id_user);

			if ($cek) {
				// proses tambah data
				// proses tambah ke models
				$getTabel = $this->M_users->hapus($id_user_edit);

				if ($getTabel == 1) {
					$hasil = [
						'code' => 201,
						'status' => 'sukses',
						'message' => 'Data Berhasil Dihapus.',
					];
				} else if ($getTabel == 2) {
					$hasil = [
						'code' => 200,
						'status' => 'error',
						'message' => 'Data Pengguna tinggal 1, jadi tidak bisa dihapus.',
					];
				} else {
					$hasil = [
						'code' => 200,
						'status' => 'error',
						'message' => 'Gagal Dihapus!',
					];
				}

			}
		}

		$this->output
			->set_content_type('application/json')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}

	public function ganti_password()
	{
		// mmbuat variabel untuk post data yang dikirim
		$password_lama = trim($this->input->post('password_lama'));
		$password_baru = trim($this->input->post('password_baru'));

		// cek apakah sudah ada isinya (keamanan)
		if ($password_baru === null || $password_baru == '' || $password_lama === null || $password_lama == '') {
			$hasil = [
				"code" => 200,
				"status" => 'error',
				"message" => 'nilai blm lengkap, ulangi lagi'
			];
		} else {

			$id_user = $this->input->post('auth_key');
			// cek autorization
			$cek = $this->M_users->ambil_user($id_user);

			if ($cek['jumlah']) {
				$get_password = $cek['data'];
				// proses cek password
				if (password_verify($password_lama, $get_password->password)) {
					$data = [
						'password' => password_hash($password_baru, PASSWORD_DEFAULT)
					];
					// proses tambah ke models
					$getTabel = $this->M_users->update_user($data, $id_user);

					if ($getTabel) {
						$hasil = [
							'code' => 201,
							'status' => 'sukses',
							'message' => 'Password Berhasil Dirubah.',
						];
					} else {
						$hasil = [
							'code' => 200,
							'status' => 'error',
							'message' => 'Password Gagal Dirubah!',
						];
					}
				} else {
					$hasil = [
						'code' => 200,
						'status' => 'error',
						'message' => 'Password lama Salah.',
						'meta' => null,
						'data' => null,
					];
				}

			}
		}

		$this->output
			->set_content_type('application/json')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}
}
