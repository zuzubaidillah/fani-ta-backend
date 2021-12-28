<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Rekap extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model('M_rekap');
		$this->load->model('M_users');
	}

	public function index()
	{
		// cek jumlah data
		$cek = $this->M_rekap->cek_data();

		// $cek 1 / 0
		if ($cek) {
			$id_user = $this->input->post('auth_key');
			// cek autorization
			$cek = $this->M_users->ambil_user($id_user);

			if ($cek['jumlah']) {
				// mengirim baris awal dan batas ke models
				$get = $this->M_rekap->ambil_dusun();
				$getJenis = $this->M_rekap->ambil_jenis_buah(1);
				$rekap = $get['data'];
				$jenis_buah = [];

				foreach ($getJenis['data'] as $key => $val) {
					$row = [];
					$row['nama_jenis_buah'] = $val->nama_jenis_buah;
					$row['id_jenis_buah'] = $val->id_jenis_buah;
					$hitung_data = $this->M_rekap->hitung_data($val->id_jenis_buah);
					$row['jumlah'] = $hitung_data['data'];
					$jenis_buah[] = $row;
				}

				// array multidimensi: array didalam array
				$hasil = [
					"data" => [
						"dusun" => $rekap,
						"jenis_buah" => $jenis_buah
					],
					"meta" => null,
					"code" => 200,
					"status" => "sukses",
					"message" => 'Data ditemukan'
				];
			} else {
				$hasil = [
					"code" => 200,
					"status" => 'error',
					"message" => 'Auth salah',
					'meta' => null,
					'data' => null,
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

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}
}
