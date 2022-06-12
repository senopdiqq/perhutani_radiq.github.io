<?php
defined('BASEPATH') or exit('No direct script access allowed');

class lap_pinjaman extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$query = $this->db->select('*, pinjaman.nama as nama_pinjaman, anggota.nama as nama_anggota')
			->from('pengajuan')
			->join('anggota', 'pengajuan.id_anggota = anggota.id_anggota')
			->join('pinjaman', 'pengajuan.id_pinjaman = pinjaman.id_pinjaman')
			->where('status', 'Ya')
			->get()->result();
		// print_r($query);

		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'admin' && $this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
		} else {
			$data = array(
				'pinjaman'	=> $query
			);
			$this->load->view('lap_pinjaman', $data);
		}
	}

	public function detail()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'admin'  && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$this->session->set_userdata('id_pengajuan_detaill', $this->input->get('id'));
		redirect('lap_pinjaman/detailPinjaman');
	}

	public function detailPinjaman()
	{
		$id = $this->session->userdata('id_pengajuan_detaill');
		// echo "string".$id;
		$cari = array('pengajuan.id_pengajuan' => $id, 'status' => 'Ya');

		$query = $this->db->select('*')
			->from('angsuran')
			->join('pengajuan', 'angsuran.id_pengajuan = pengajuan.id_pengajuan')
			->where('angsuran.id_pengajuan', $id)
			->get()->result();

		foreach ($query as $res) {
			$nama_anggota = $res->id_anggota;
		}

		$getNama = $this->db->select('*')
			->from('pengajuan')
			->where('id_pengajuan', $id)
			->get()->result();

		foreach ($getNama as $res2) {
			$id_get_nama = $res2->id_anggota;
		}

		$getNama2 = $this->db->select('*')
			->from('anggota')
			->where('id_anggota', $id_get_nama)
			->get()->result();

		foreach ($getNama2 as $resn) {
			$nama_asli = $resn->nama;
		}

		$data = array(
			'detail'	=> $query,
			'detail_nama' => $nama_asli,
		);

		$this->load->view('lap_pinjamanDetail', $data);
	}

	public function hapus()
	{
		$this->db->delete('pengajuan', array('id_anggota' => $this->input->post('idpop')));
		redirect('lap_pinjaman');
	}
}
