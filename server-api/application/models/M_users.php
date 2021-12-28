<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_users extends CI_Model
{
	public $table = 'users';

	public function cek_data()
	{
		$table = $this->table;
		$query = $this->db->query("SELECT id_user FROM $table");

		if ($query->num_rows() >= 1) {
			return 1;
		} else {
			return 0;
		}
	}

	public function cek_username($n, $id=0)
	{
		$table = $this->table;
		if ($id==0){
			$query = $this->db->query("SELECT id_user FROM $table WHERE username='$n'");
		}else{
			$query = $this->db->query("SELECT id_user FROM $table WHERE username='$n' AND id_user!='$id'");
		}

		if ($query->num_rows() >= 1) {
			return 1;
		} else {
			return 0;
		}
	}

	public function cek_id($id_user)
	{
		$table = $this->table;
		$query = $this->db->query("SELECT id_user FROM $table WHERE id_user='$id_user'");

		if ($query->num_rows() >= 1) {
			return 1;
		} else {
			return 0;
		}
	}

	public function post_user($data)
	{
		$query = $this->db->insert($this->table, $data);

		if ($query) {
			return 1;
		} else {
			return 0;
		}
	}

	public function get_password($n)
	{
		$table = $this->table;
		$query = $this->db->query("SELECT id_user, password, nama FROM $table WHERE username='$n'");

		if ($query->num_rows() >= 1) {
			return $query->row();
		} else {
			return 0;
		}
	}

	public function get_all($id=0)
	{
		// deklarasikan variabel global agar bisa dipakai di dalam fungsi ini
		$table = $this->table;

		if ($id==0) {
			// sql
			$query = $this->db->query("SELECT * FROM $table ORDER BY date_create DESC");
		}else{
			$query = $this->db->query("SELECT * FROM $table WHERE id_user!='$id' ORDER BY date_create DESC");
		}
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

	public function cek_id_user($id_user = 0)
	{
		$table = $this->table;
		$query = $this->db->query("SELECT idUser FROM $table WHERE idUser='$id_user'");

		if ($query->num_rows() >= 1) {
			return [
				"jumlah" => 1,
				"data" => 1,
			];
		} else {
			return [
				"jumlah" => 0,
				"data" => 0,
			];
		}
	}

	public function cari_nama_pengguna_samdengan($kata_kunci, $id=0)
	{
		$table = $this->table;
		if ($id==0){
			$query = $this->db->query("SELECT * FROM $table WHERE nama='$kata_kunci'");
		}else{
			$query = $this->db->query("SELECT * FROM $table WHERE nama='$kata_kunci' AND id_user!='$id'");
		}

		if ($query->num_rows() >= 1) {
			return [
				"jumlah" => $query->num_rows(),
				"data" => $query->result()
			];
		} else {
			return [
				"jumlah" => 0,
				"data" => 0
			];
		}
	}

	public function ambil_user($id_user)
	{
		$table = $this->table;
		$query = $this->db->query("SELECT * FROM $table WHERE id_user='$id_user'");

		if ($query->num_rows() >= 1) {
			return [
				"jumlah" => $query->num_rows(),
				"data" => $query->row()
			];
		} else {
			return [
				"jumlah" => 0,
				"data" => 0
			];
		}
	}

	public function update_user($data, $id)
	{
		$this->db->where('id_user', $id);
		$query = $this->db->update($this->table, $data);

		if ($query) {
			return 1;
		} else {
			return 0;
		}
	}

	public function hapus($id)
	{
		/*cek apakah data kurang dari 1*/
		$table = $this->table;
		$qr = $this->db->query("SELECT * FROM $table");
		if ($qr->num_rows()<=1){
			return 2;
		}else {
			$this->db->where('id_user', $id);
			$query = $this->db->delete($table);

			if ($query) {
				return 1;
			} else {
				return 0;
			}
		}
	}
}
