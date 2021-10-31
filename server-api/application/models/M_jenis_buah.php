<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_jenis_buah extends CI_Model
{

  public $table = 'jenis_buah';

  public function cek_data()
  {
    $table = $this->table;
    $query = $this->db->query("SELECT id_jenis_buah FROM $table");

    if ($query->num_rows() >= 1) {
      return 1;
    } else {
      return 0;
    }
  }

  public function get_all($h, $b)
  {
    $table = $this->table;
    $countSQL = $this->db->query("SELECT count(nama_jenis_buah) as jumlah FROM $table");

    $query = $this->db->query("SELECT
            *
        FROM
            $table
        ORDER BY date_create DESC LIMIT $h, $b");


    if ($query->num_rows() >= 1) {
      $jumlah = $countSQL->row();

      return [
        "jumlah" => $jumlah->jumlah,
        "data" => $query->result()
      ];
    } else {
      return [
        "jumlah" => 0,
        "data" => 0,
      ];
    }
  }

  public function cari_nama_jenis_buah($kata_kunci)
  {
    $table = $this->table;
    $query = $this->db->query("SELECT
      *
    FROM
      $table
    WHERE nama_jenis_buah LIKE '%$kata_kunci%'
    ORDER BY nama_jenis_buah ASC");

    $countSQL = $this->db->query("SELECT count(nama_jenis_buah) as jumlah FROM $table WHERE nama_jenis_buah LIKE '%$kata_kunci%'");

    if ($query->num_rows() >= 1) {
      $jumlah = $countSQL->row();

      return [
        "jumlah" => $jumlah->jumlah,
        "data" => $query->result()
      ];
    } else {
      return [
        "jumlah" => 0,
        "data" => 0
      ];
    }
  }

  public function cari_nama_jenis_buah_samdengan($kata_kunci)
  {
    $table = $this->table;
    $query = $this->db->query("SELECT
      *
    FROM
      $table
    WHERE nama_jenis_buah='$kata_kunci'
    ORDER BY nama_jenis_buah ASC");

    $countSQL = $this->db->query("SELECT count(nama_jenis_buah) as jumlah FROM $table WHERE nama_jenis_buah='$kata_kunci'");

    if ($query->num_rows() >= 1) {
      $jumlah = $countSQL->row();

      return [
        "jumlah" => $jumlah->jumlah,
        "data" => $query->result()
      ];
    } else {
      return [
        "jumlah" => 0,
        "data" => 0
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
}
