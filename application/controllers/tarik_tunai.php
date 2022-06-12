<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Tarik_tunai extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='karyawan') {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('tarik_tunai');
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
			$tanggal = $this->input->post('thn') . '-' . $this->input->post('bln') . '-' . $this->input->post('tgl');
			
			$id_a   = $this->input->post('anggota');
			$jumlah = $this->input->post('besar');

			$getSaldo = $this->db->get_where('tabungan',['id_anggota' => $id_a])->result();

			foreach($getSaldo as $res){
				$saldo = $res->jumlah;
			}

			$limit_saldo = $saldo/2;

			if($jumlah <= $limit_saldo){
				
				$getUang = array(
					'tanggal' => $tanggal,
					'id_anggota' => $id_a,
					'jumlah' => $jumlah,
				);
				$add = $this->db->insert('ambil_tabungan',$getUang);

				$saldo_akhir = $saldo-$jumlah;

				if($add){
					$updateSaldo = array(
						'jumlah' => $saldo_akhir,
					);
					$this->db->where('id_anggota',$id_a)->update('tabungan',$updateSaldo);

					$log = array(
						'action' => 'Update Data Tabungan',
						'id_admin' => $this->session->userdata('id_user')
					);
					$this->db->insert('logs', $log);
				}

			}else{

				

			}

				
			
				
			$result = '1#tarik_tunai';

			echo json_encode($result);
			// redirect('tabungan');
		}
		
	}
?>