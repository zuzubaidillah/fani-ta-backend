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

    public function cek_username($n)
    {
        $table = $this->table;
        $query = $this->db->query("SELECT id_user FROM $table WHERE username='$n'");

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

    public function get_all($h, $b)
    {
        // deklarasikan variabel global agar bisa dipakai di dalam fungsi ini
        $table = $this->table;

        // sql
        // $query = $this->db->query("SELECT * FROM $table ORDER BY tanggal_kunj DESC LIMIT $h, $b");
        $query = $this->db->query("SELECT
            tabelKunjungan.idKunjungan,
            tabelKunjungan.nama as nama_pengunjung,
            tabelKunjungan.tanggal_kunj AS tanggal,
            tabelKunjungan.no_telp,
            tabelKunjungan.detail_acara,
            tabelUser.nama_user,
            tabelKota.nama_kota,
            tabelDaerah.nama_daerah,
            tabelKeperluan.nama_keperluan,
            tabelInstansi.nama_instansi
        FROM
            $table
        INNER JOIN tabelDaerah ON tabelKunjungan.idDaerah = tabelDaerah.idDerah
        INNER JOIN tabelKota ON tabelDaerah.idKota=tabelKota.idKota
        INNER JOIN tabelKeperluan ON tabelKunjungan.idKeperluan=tabelKeperluan.idKeperluan
        INNER JOIN tabelInstansi ON tabelKunjungan.idInstansi=tabelInstansi.idInstansi
        INNER JOIN tabelUser ON tabelKunjungan.idUser=tabelUser.idUser
        ORDER BY tabelKunjungan.tanggal_kunj DESC LIMIT $h, $b");

        $countSQL = $this->db->query("SELECT count(nama) as jumlah FROM $table");

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
}
