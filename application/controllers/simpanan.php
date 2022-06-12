<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Simpanan extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='admin'&&$this->session->userdata('user')!='bendahara')) {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('simpanan');
			}		
		}
		
		public function tambah() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='admin'&&$this->session->userdata('user')!='bendahara')) {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$sql = $this->db->get_where('simpanan', array('nama' => $this->input->post('nama')));
			$row = $sql->num_rows();
			
			if ($sql->num_rows()>0) {
				$result = '0#Nama Sudah Digunakan#nama';
			} else {
				$data = array(
					'nama' => $this->input->post('nama'),
					'besar' => $this->input->post('besar')
				);
				$this->db->insert('simpanan', $data);

				$log = array(
					'action' => 'Tambah Data Simpanan',
					'id_admin' => $this->session->userdata('id_user')
				);
				$this->db->insert('logs', $log);
				
				$result = '1#simpanan';
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
				$this->session->set_userdata('id_simpanan', $this->input->get('id'));
				redirect('simpanan?p=ubah');
			} else {
				$sql = $this->db->get_where('simpanan', array('nama' => $this->input->post('nama'),'id_simpanan!=' => $this->session->userdata('id_simpanan')));
				if ($sql->num_rows()>0) {
					$result = '0#Nama Sudah Digunakan#user';
				} else {					
					$data = array(
						'nama' => $this->input->post('nama'),
						'besar' => $this->input->post('besar')
					);
					$this->db->where('id_simpanan', $this->session->userdata('id_simpanan'));
					$this->db->update('simpanan', $data);

					$log = array(
						'action' => 'Ubah Data Simpanan',
						'id_admin' => $this->session->userdata('id_user')
					);
					$this->db->insert('logs', $log);				
				
					$result = '1#simpanan';
				}
			
				echo json_encode($result);			
			}
		}

		public function hapus() {
			$this->db->delete('simpanan', array('id_simpanan' => $this->input->post('idpop')));
			
			$log = array(
				'action' => 'Hapus Data Simpanan',
				'id_admin' => $this->session->userdata('id_user')
			);
			$this->db->insert('logs', $log);				

			redirect('simpanan');
		}
	}
?>