<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Not extends CI_Controller
{
	public function index()
	{
		$hasil = [
			"code" => 404,
			"status" => 'Not Found',
			"message" => 'Halman Kosong',
			'meta' => null,
			'data' => null,
		];

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_status_header($hasil['code'], $hasil['message'])
			->set_output(json_encode($hasil, JSON_PRETTY_PRINT));
	}
}
