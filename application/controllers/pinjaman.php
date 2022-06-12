<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Pinjaman extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='admin'&&$this->session->userdata('user')!='bendahara')) {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('pinjaman');
			}		
		}
		
		public function tambah() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='admin'&&$this->session->userdata('user')!='bendahara')) {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$sql = $this->db->get_where('pinjaman', array('nama' => $this->input->post('nama')));
			$row = $sql->num_rows();
			
			if ($sql->num_rows()>0) {
				$result = '0#Nama Sudah Digunakan#nama';
			} else {
				$data = array(
					'nama' => $this->input->post('nama'),
					'maks' => $this->input->post('maks'),
					'mins' => $this->input->post('mins')
				);
				$this->db->insert('pinjaman', $data);

				$log = array(
					'action' => 'Tambah Data Pinjaman',
					'id_admin' => $this->session->userdata('id_user')
				);
				$this->db->insert('logs', $log);
				
				$result = '1#pinjaman';
			}
		
			echo json_encode($result);
		}
		
		public function ubah() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='admin'&&$this->session->userdata('user')!='bendahara')) {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			if ($this->input->get('id')!=null) {
				$this->session->set_userdata('id_pinjaman', $this->input->get('id'));
				redirect('pinjaman?p=ubah');
			} else {
				$sql = $this->db->get_where('pinjaman', array('nama' => $this->input->post('nama'),'id_pinjaman!=' => $this->session->userdata('id_pinjaman')));
				if ($sql->num_rows()>0) {
					$result = '0#Nama Sudah Digunakan#user';
				} else {					
					$data = array(
						'nama' => $this->input->post('nama'),
						'maks' => $this->input->post('maks'),
						'mins' => $this->input->post('mins')
					);
					$this->db->where('id_pinjaman', $this->session->userdata('id_pinjaman'));
					$this->db->update('pinjaman', $data);

					$log = array(
						'action' => 'Ubah Data Pinjaman',
						'id_admin' => $this->session->userdata('id_user')
					);
					$this->db->insert('logs', $log);				
				
					$result = '1#pinjaman';
				}
			
				echo json_encode($result);			
			}
		}

		public function hapus() {
			$this->db->delete('pinjaman', array('id_pinjaman' => $this->input->post('idpop')));
			
			$log = array(
				'action' => 'Hapus Data Pinjaman',
				'id_admin' => $this->session->userdata('id_user')
			);
			$this->db->insert('logs', $log);				

			redirect('pinjaman');
		}
	}
?>