<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Tabungan extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='karyawan') {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('tabungan');
			}		
		}
		
		public function cari() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='karyawan') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$this->session->set_userdata('cari_tabungan', $this->input->post('nama'));
			$result = true;
			
			echo json_encode($result);
		}
		
		public function proses() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='karyawan') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			// $id_simpanan = explode('|', $this->input->post('simpanan'))[0];
			$id_simpanan = $this->input->post('simpanan');
			$tanggal = $this->input->post('thn') . '-' . $this->input->post('bln') . '-' . $this->input->post('tgl');
			
			$cek_tabungan = $this->db->get_where('tabungan',['id_anggota' => $this->input->post('anggota')])->row();

			if(empty($cek_tabungan)){

				$data = array(
					'id_anggota' => $this->input->post('anggota'),
					'jumlah' => $this->input->post('besar'),
					'id_admin' => $this->session->userdata('id_user')
				);
				$this->db->insert('tabungan', $data);

				$data2 = array(
					'id_anggota' => $this->input->post('anggota'),
					'id_simpanan' => $id_simpanan,
					'tanggal' => $tanggal,
					'jumlah' => $this->input->post('besar'),
				);
				$this->db->insert('list_tabungan',$data2);

				$log = array(
					'action' => 'Tambah Data Tabungan',
					'id_admin' => $this->session->userdata('id_user')
				);
				$this->db->insert('logs', $log);

			}else{

				$tambah_saldo = $this->db->get_where('tabungan',['id_anggota' => $this->input->post('anggota')])->result();

				foreach ($tambah_saldo as $key ) {
					$saldo_awal = $key->jumlah;
				}

				$jt = $this->input->post('besar');

				$saldo_akhir = $jt+$saldo_awal;
				
				$data = array(
					'jumlah' => $saldo_akhir,
				);
				$this->db->where('id_anggota',$this->input->post('anggota'))->update('tabungan', $data);

				$data2 = array(
					'id_anggota' => $this->input->post('anggota'),
					'id_simpanan' => $id_simpanan,
					'tanggal' => $tanggal,
					'jumlah' => $this->input->post('besar'),
				);
				$this->db->insert('list_tabungan',$data2);

				$log = array(
					'action' => 'Update Data Tabungan',
					'id_admin' => $this->session->userdata('id_user')
				);
				$this->db->insert('logs', $log);

			}

			
				
			
				
			$result = '1#tabungan';

			echo json_encode($result);
			// redirect('tabungan');
		}
	}
?>