<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_lokasi extends CI_Model
{

  public $table = 'set_titik_lokasi';

  public function cek_data()
  {
    $table = $this->table;
    $query = $this->db->query("SELECT id_set_titik_lokasi FROM $table");

    if ($query->num_rows() >= 1) {
      return 1;
    } else {
      return 0;
    }
  }

  public function get_all($h, $b)
  {
    // deklarasikan variabel global agar bisa dipakai di dalam fungsi ini
    $table = $this->table;
    $countSQL = $this->db->query("SELECT count(nama_buah) as jumlah FROM $table");

    // sql
    // $query = $this->db->query("SELECT * FROM $table ORDER BY tanggal_kunj DESC LIMIT $h, $b");
    $query = $this->db->query("SELECT
            set_titik_lokasi.*,
            users.nama,
            dusun.nama_dusun,
            pemilik_tanah.nama_pemilik,
            jenis_buah.nama_jenis_buah,
            dusun.nama_desa
        FROM
            set_titik_lokasi
        INNER JOIN dusun ON set_titik_lokasi.id_dusun = dusun.id_dusun
        INNER JOIN pemilik_tanah ON set_titik_lokasi.id_pemilik_tanah = pemilik_tanah.id_pemilik_tanah
        INNER JOIN jenis_buah ON set_titik_lokasi.id_jenis_buah = jenis_buah.id_jenis_buah
        INNER JOIN users ON set_titik_lokasi.id_users = users.id_user
        ORDER BY set_titik_lokasi.id_dusun ASC LIMIT $h, $b");


    // num_rows untuk menghitung banyaknya data
    if ($query->num_rows() >= 1) {
      // menghitung jumlah data tanpa melakukan limit
      $jumlah = $countSQL->row();

      // return mengembalikan nilai
      return [
        "jumlah" => $jumlah->jumlah,
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

  public function get_cari($h, $b, $s)
  {
    // deklarasikan variabel global agar bisa dipakai di dalam fungsi ini
    $table = $this->table;
    // $countSQL = $this->db->query("SELECT count(nama_buah) as jumlah FROM $table");
    if ($s == '') {
      $where = '';
    } else {
      $where = "where 
      pemilik_tanah.nama_pemilik LIKE '%$s%' OR users.nama LIKE '%$s%' OR dusun.nama_dusun LIKE '%$s%' OR jenis_buah.nama_jenis_buah LIKE '%$s%' ";
    }

    // sql
    // $query = $this->db->query("SELECT * FROM $table ORDER BY tanggal_kunj DESC LIMIT $h, $b");
    $query = $this->db->query("SELECT
        set_titik_lokasi.*,
        users.nama,
        dusun.nama_dusun,
        pemilik_tanah.nama_pemilik,
        jenis_buah.nama_jenis_buah,
        dusun.nama_desa
    FROM
        set_titik_lokasi
    INNER JOIN dusun ON set_titik_lokasi.id_dusun = dusun.id_dusun
    INNER JOIN pemilik_tanah ON set_titik_lokasi.id_pemilik_tanah = pemilik_tanah.id_pemilik_tanah
    INNER JOIN jenis_buah ON set_titik_lokasi.id_jenis_buah = jenis_buah.id_jenis_buah
    INNER JOIN users ON set_titik_lokasi.id_users = users.id_user
      $where
    ORDER BY set_titik_lokasi.id_dusun ASC LIMIT $h, $b");

    // num_rows untuk menghitung banyaknya data
    if ($query->num_rows() >= 1) {
      $jumlah = $query->num_rows();

      // return mengembalikan nilai
      return [
        "jumlah" => $jumlah,
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

  public function tambah_data($data)
  {
    $query = $this->db->insert($this->table, $data);

    if ($query) {
      return 1;
    } else {
      return 0;
    }
  }

  public function where_id($id)
  {
    $table = $this->table;
    $query = $this->db->query("SELECT
        set_titik_lokasi.*,
        users.nama,
        dusun.nama_dusun,
        pemilik_tanah.nama_pemilik,
        jenis_buah.nama_jenis_buah,
        dusun.nama_desa
    FROM
        set_titik_lokasi
    INNER JOIN dusun ON set_titik_lokasi.id_dusun = dusun.id_dusun
    INNER JOIN pemilik_tanah ON set_titik_lokasi.id_pemilik_tanah = pemilik_tanah.id_pemilik_tanah
    INNER JOIN jenis_buah ON set_titik_lokasi.id_jenis_buah = jenis_buah.id_jenis_buah
    INNER JOIN users ON set_titik_lokasi.id_users = users.id_user
      WHERE set_titik_lokasi.id_set_titik_lokasi='$id'");

    if ($query->num_rows() >= 1) {
      // return mengembalikan nilai
      return [
        "jumlah" => 1,
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
