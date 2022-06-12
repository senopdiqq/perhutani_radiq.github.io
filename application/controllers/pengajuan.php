<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
		} else {
			$this->load->view('pengajuan');
		}
	}

	public function cari()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$this->session->set_userdata('cari_pengajuan', $this->input->post('nama'));
		$result = true;

		echo json_encode($result);
	}

	public function proses()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$tanggal = $this->input->post('thn') . '-' . $this->input->post('bln') . '-' . $this->input->post('tgl');
		$id_anggota = explode('|', $this->input->post('anggota'))[0];
		$id_pinjaman = explode('|', $this->input->post('pinjaman'))[0];

		$data = array(
			'tanggal' => $tanggal,
			'id_anggota' => $id_anggota,
			'id_pinjaman' => $id_pinjaman,
			'jumlah' => (int)$this->input->post('besar'),
			'tenor' => (int)$this->input->post('waktu'),
			'sisa' => (int)$this->input->post('waktu'),
			'denda' => (int)$this->input->post('denda'),
			'status' => 'Tidak',
			'id_admin' => $this->session->userdata('id_user')
		);
		$this->db->insert('pengajuan', $data);

		$log = array(
			'action' => 'Tambah Data Pengajuan',
			'id_admin' => $this->session->userdata('id_user')
		);
		$this->db->insert('logs', $log);

		$result = '1#pengajuan';

		echo json_encode($result);
	}

	public function detail()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$this->session->set_userdata('id_pengajuan', $this->input->get('id'));
		redirect('pengajuan?p=detail');
	}

	public function terima()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$data = array(
			'status' => 'Ya'
		);
		$this->db->where('id_pengajuan', $this->session->userdata('id_pengajuan'));
		$this->db->update('pengajuan', $data);

		$result = '1#pengajuan';

		echo json_encode($result);
	}

	public function angsur()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$sql = $this->db->get_where('pengajuan', array('id_pengajuan' => $this->session->userdata('id_pengajuan')));
		$row = $sql->row();
		$sisa = (int)$row->sisa - 1;

		$data = array(
			'sisa' => $sisa
		);
		$this->db->where('id_pengajuan', $this->session->userdata('id_pengajuan'));
		$this->db->update('pengajuan', $data);

		$data = array(
			'id_pengajuan' => $this->session->userdata('id_pengajuan'),
			'jumlah' => (int)str_replace(',', '', $this->input->post('jmlangsur')),
			'id_admin' => $this->session->userdata('id_user')
		);
		$this->db->insert('angsuran', $data);

		$log = array(
			'action' => 'Tambah Data Angsuran',
			'id_admin' => $this->session->userdata('id_user')
		);
		$this->db->insert('logs', $log);

		$result = '1#pengajuan';

		echo json_encode($result);
	}

	public function bayarLunas()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'bendahara' && $this->session->userdata('user') != 'karyawan')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$data = array(
			'sisa' => 0
		);
		$this->db->where('id_pengajuan', $this->session->userdata('id_pengajuan'));
		$this->db->update('pengajuan', $data);

		$data = array(
			'id_pengajuan' 	=> $this->session->userdata('id_pengajuan'),
			'jumlah' 		=> (int)str_replace(',', '', $this->input->post('lunas')),
			'id_admin' 		=> $this->session->userdata('id_user')
		);
		$this->db->insert('angsuran', $data);

		$log = array(
			'action' 	=> 'Tambah Data Angsuran',
			'id_admin' 	=> $this->session->userdata('id_user')
		);
		$this->db->insert('logs', $log);

		$result = '1#pengajuan';

		echo json_encode($result);
	}
}
