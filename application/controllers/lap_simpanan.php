<?php
defined('BASEPATH') or exit('No direct script access allowed');

class lap_simpanan extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$query = $this->db->select('*')
			->from('tabungan')
			->join('anggota', 'anggota.id_anggota=tabungan.id_anggota')
			->get()->result();
		// print_r($query);

		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'admin' && $this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
		} else {
			$data = array(
				'simpanan'	=> $query
			);
			$this->load->view('lap_simpanan', $data);
		}
	}

	public function detail()
	{
		$id = $this->input->get('id');

		$nama = $this->db->select('*')
			->from('anggota')
			->where('id_anggota', $id)
			->get()->result();

		foreach ($nama as $res) {
			$getNama = $res->nama;
		}

		$query = $this->db->select('*')
			->from('list_tabungan')
			->join('simpanan', 'simpanan.id_simpanan=list_tabungan.id_simpanan')
			->where('list_tabungan.id_anggota', $id)
			->get()->result();

		$trt = $this->db->select('*')
			->from('ambil_tabungan')
			->where('id_anggota', $id)
			->get()->result();

		$data = array(
			'id_simpan'  => $id,
			'detail_nama' => $getNama,
			'detail' => $query,
			'tarik_tunai' => $trt,
		);

		$this->load->view('lap_DetailSimpanan', $data);
	}
}
