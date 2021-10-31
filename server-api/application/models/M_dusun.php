<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dusun extends CI_Model
{

  public $table = 'dusun';

  public function cek_data()
  {
    $table = $this->table;
    $query = $this->db->query("SELECT id_dusun FROM $table");

    if ($query->num_rows() >= 1) {
      return 1;
    } else {
      return 0;
    }
  }

  public function get_all($h, $b)
  {
    $table = $this->table;
    $countSQL = $this->db->query("SELECT count(nama_dusun) as jumlah FROM $table");

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

  public function cari_nama_dusun($kata_kunci)
  {
    $table = $this->table;
    $query = $this->db->query("SELECT
      *
    FROM
      $table
    WHERE nama_dusun LIKE '%$kata_kunci%'
    ORDER BY nama_dusun ASC");

    $countSQL = $this->db->query("SELECT count(nama_dusun) as jumlah FROM $table WHERE nama_dusun LIKE '%$kata_kunci%'");

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

  public function cari_nama_dusun_samdengan($kata_kunci)
  {
    $table = $this->table;
    $query = $this->db->query("SELECT
      *
    FROM
      $table
    WHERE nama_dusun='$kata_kunci'
    ORDER BY nama_dusun ASC");

    $countSQL = $this->db->query("SELECT count(nama_dusun) as jumlah FROM $table WHERE nama_dusun='$kata_kunci'");

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
