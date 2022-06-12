<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Anggota extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'admin' && $this->session->userdata('user') != 'bendahara')) {
			$this->session->sess_destroy();
			redirect('home');
		} else {

			$query = $this->db->select('*')
				->from('pengajuan')
				->where('status', 'Ya')
				->get()->result();

			$this->load->view('anggota');
		}
	}

	public function cari()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'admin' && $this->session->userdata('user') != 'bendahara')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$this->session->set_userdata('cari_anggota', $this->input->post('nama'));
		$result = true;

		echo json_encode($result);
	}

	public function tambah()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'admin' && $this->session->userdata('user') != 'bendahara')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		$row1 = 0;
		if (strlen($this->input->post('nip')) > 0) {
			$sql1 = $this->db->get_where('anggota', array('nip' => $this->input->post('nip')));
			$row1 = $sql1->num_rows();
		}

		$sql2 = $this->db->get_where('anggota', array('nama' => $this->input->post('nama')));
		$row2 = $sql2->num_rows();

		if ($row1 > 0 || $row2 > 0) {
			if ($row1 > 0) {
				$result = '0#NIP Sudah Digunakan#nip';
			} else if ($row2 > 0) {
				$result = '0#Nama Lengkap Sudah Digunakan#nama';
			}
		} else {
			$gol = $this->input->post('gol1') . $this->input->post('gol2');

			$data = array(
				'nip' => $this->input->post('nip'),
				'nama' => $this->input->post('nama'),
				'gol' => $gol,
				'jab' => $this->input->post('jab'),
				'upah' => $this->input->post('upah')
			);
			$this->db->insert('anggota', $data);

			$tanggal = date('Y-m-d');

			$src = "SELECT id_anggota FROM anggota ORDER BY id_anggota DESC LIMIT 0,1";
			$sql = $this->db->query($src);
			$row = $sql->row();
			$id_anggota = $row->id_anggota;

			$src = "SELECT besar FROM simpanan WHERE id_simpanan = '" . $this->input->post('awal') . "'";
			$sql = $this->db->query($src);
			$row = $sql->row();
			$jumlah = $row->besar;

			$data = array(
				'id_simpanan' => $this->input->post('awal'),
				'tanggal' => $tanggal,
				'id_anggota' => $id_anggota,
				'jumlah' => $jumlah,
				'id_admin' => $this->session->userdata('id_user')
			);
			$this->db->insert('tabungan', $data);

			$log = array(
				'action' => 'Tambah Data Anggota',
				'id_admin' => $this->session->userdata('id_user')
			);
			$this->db->insert('logs', $log);

			$result = '1#anggota';
		}

		echo json_encode($result);
	}

	public function ubah()
	{
		if (empty($this->session->userdata('user')) || ($this->session->userdata('user') != 'admin' && $this->session->userdata('user') != 'bendahara')) {
			$this->session->sess_destroy();
			redirect('home');
			exit();
		}

		if ($this->input->get('id') != null) {
			$this->session->set_userdata('id_anggota', $this->input->get('id'));
			redirect('anggota?p=ubah');
		} else {
			$row1 = 0;
			if (strlen($this->input->post('nip')) > 0) {
				$sql1 = $this->db->get_where('anggota', array('nip' => $this->input->post('nip'), 'id_anggota!=' => $this->session->userdata('id_anggota')));
				$row1 = $sql1->num_rows();
			}

			$sql2 = $this->db->get_where('anggota', array('nama' => $this->input->post('nama'), 'id_anggota!=' => $this->session->userdata('id_anggota')));
			$row2 = $sql2->num_rows();

			if ($row1 > 0 || $row2 > 0) {
				if ($row1 > 0) {
					$result = '0#NIP Sudah Digunakan#nip';
				} else if ($row2 > 0) {
					$result = '0#Nama Lengkap Sudah Digunakan#nama';
				}
			} else {
				$gol = $this->input->post('gol1') . $this->input->post('gol2');

				$data = array(
					'nip' => $this->input->post('nip'),
					'nama' => $this->input->post('nama'),
					'gol' => $gol,
					'jab' => $this->input->post('jab'),
					'upah' => $this->input->post('upah')
				);
				$this->db->where('id_anggota', $this->session->userdata('id_anggota'));
				$this->db->update('anggota', $data);

				$log = array(
					'action' => 'Ubah Data Anggota',
					'id_admin' => $this->session->userdata('id_user')
				);
				$this->db->insert('logs', $log);

				$result = '1#anggota';
			}

			echo json_encode($result);
		}
	}

	public function hapus()
	{

		$i = $this->input->post('idpop');
		$isi = array(
			'id_anggota' => $i,
			'sisa >' => '0',
		);
		$query = $this->db->select('*')
			->from('pengajuan')
			->where($isi)
			->get()->row();

		if (empty($query)) {

			// echo "aaaaaaaaaa".$i;
			$this->db->delete('anggota', array('id_anggota' => $this->input->post('idpop')));

			$this->db->delete('tabungan', array('id_anggota' => $this->input->post('idpop')));

			$this->db->delete('pengajuan', array('id_anggota' => $this->input->post('idpop')));
			redirect('anggota');
		} else {
			// echo "bbbbbbbbbbb".$i;

			echo "<script>alert('Pinjaman Belum Lunas'),windo;</script>" . redirect('anggota');

			$log = array(
				'action' => 'Hapus Data Anggota',
				'id_admin' => $this->session->userdata('id_user')
			);
			$this->db->insert('logs', $log);

			redirect('anggota');
		}
	}
}
