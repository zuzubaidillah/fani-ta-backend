<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_rekap extends CI_Model
{
	public $table = 'set_titik_lokasi';

	public function cek_data()
	{
		$table = $this->table;
		$query = $this->db->query("SELECT id_users FROM $table");

		if ($query->num_rows() >= 1) {
			return 1;
		} else {
			return 0;
		}
	}

	public function ambil_jenis_buah($id)
	{
		// deklarasikan variabel global agar bisa dipakai di dalam fungsi ini
		$table = $this->table;

		$query = $this->db->query("SELECT id_jenis_buah, nama_jenis_buah FROM jenis_buah ORDER BY nama_jenis_buah ASC, id_jenis_buah ASC");

		// num_rows untuk menghitung banyaknya data
		if ($query->num_rows() >= 1) {

			// return mengembalikan nilai
			return [
				"jumlah" => $query->num_rows(),
				"data" => $query->result()
			];
		} else {
			// return mengembalikan nilai
			return [
				"jumlah" => 0,
				"data" => 0,
			];
		}
	}

	public function hitung_data($id_jenis_buah)
	{
		// deklarasikan variabel global agar bisa dipakai di dalam fungsi ini
		$table = $this->table;

//		$id_jenis_buah = 44067177;
		$data_dusun = $this->ambil_dusun();
		$data_jml = [];
		foreach ($data_dusun['data'] as $key => $val) {
			$id = $val->id_dusun;
			$query = $this->db->query("SELECT count(*) as jml FROM set_titik_lokasi WHERE id_jenis_buah='$id_jenis_buah' AND id_dusun='$id'")->row();
			$data_jml[] = $query;
		}
		return [
			"jumlah" => 1,
			"data" => $data_jml
		];
	}

	public function ambil_dusun()
	{
		// deklarasikan variabel global agar bisa dipakai di dalam fungsi ini
		$table = $this->table;

		$query = $this->db->query("SELECT * FROM dusun ORDER BY nama_dusun ASC, id_dusun ASC");
		// num_rows untuk menghitung banyaknya data
		if ($query->num_rows() >= 1) {

			// return mengembalikan nilai
			return [
				"jumlah" => $query->num_rows(),
				"data" => $query->result()
			];
		} else {
			// return mengembalikan nilai
			return [
				"jumlah" => 0,
				"data" => 0,
			];
		}
	}
}
