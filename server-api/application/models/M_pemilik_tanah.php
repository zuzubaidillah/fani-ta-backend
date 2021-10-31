<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_pemilik_tanah extends CI_Model
{

  public $table = 'pemilik_tanah';

  public function cek_data()
  {
    $table = $this->table;
    $query = $this->db->query("SELECT id_pemilik_tanah FROM $table");

    if ($query->num_rows() >= 1) {
      return 1;
    } else {
      return 0;
    }
  }

  public function get_all($h, $b)
  {
    $table = $this->table;
    $countSQL = $this->db->query("SELECT count(nama_pemilik) as jumlah FROM $table");

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

  public function cari_nama_pemilik($kata_kunci)
  {
    $table = $this->table;
    $query = $this->db->query("SELECT
      *
    FROM
      $table
    WHERE nama_pemilik LIKE '%$kata_kunci%'
    ORDER BY nama_pemilik ASC");

    $countSQL = $this->db->query("SELECT count(nama_pemilik) as jumlah FROM $table WHERE nama_pemilik LIKE '%$kata_kunci%'");

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

  public function cari_nama_pemilik_samdengan($kata_kunci)
  {
    $table = $this->table;
    $query = $this->db->query("SELECT
      *
    FROM
      $table
    WHERE nama_pemilik='$kata_kunci'
    ORDER BY nama_pemilik ASC");

    $countSQL = $this->db->query("SELECT count(nama_pemilik) as jumlah FROM $table WHERE nama_pemilik='$kata_kunci'");

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
