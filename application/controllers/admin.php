<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Admin extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='admin') {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('admin');
			}		
		}
		
		public function tambah() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='admin') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$sql = $this->db->get_where('admin', array('user' => $this->input->post('user')));
			$row = $sql->num_rows();
			
			if ($sql->num_rows()>0) {
				$result = '0#Username Sudah Digunakan#user';
			} else {
				$data = array(
					'user' => $this->input->post('user'),
					'pass' => MD5($this->input->post('pass')),
					'stts' => $this->input->post('stts')
				);
				$this->db->insert('admin', $data);

				$log = array(
					'action' => 'Tambah Data Admin',
					'id_admin' => $this->session->userdata('id_user')
				);
				$this->db->insert('logs', $log);
				
				$result = '1#admin';
			}
		
			echo json_encode($result);
		}
		
		public function ubah() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='admin') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			if ($this->input->get('id')!=null) {
				$this->session->set_userdata('id_admin', $this->input->get('id'));
				redirect('admin?p=ubah');
			} else {
				$sql = $this->db->get_where('admin', array('user' => $this->input->post('user'),'id_admin!=' => $this->session->userdata('id_admin')));
				if ($sql->num_rows()>0) {
					$result = '0#Username Sudah Digunakan#user';
				} else {					
					$data = array(
						'user' => $this->input->post('user'),
						'pass' => MD5($this->input->post('pass')),
						'stts' => $this->input->post('stts')
					);
					$this->db->where('id_admin', $this->session->userdata('id_admin'));
					$this->db->update('admin', $data);

					$log = array(
						'action' => 'Ubah Data Admin',
						'id_admin' => $this->session->userdata('id_user')
					);
					$this->db->insert('logs', $log);				
				
					$result = '1#admin';
				}
			
				echo json_encode($result);			
			}
		}

		public function hapus() {
			$this->db->delete('admin', array('id_admin' => $this->input->post('idpop')));
			
			$log = array(
				'action' => 'Hapus Data Admin',
				'id_admin' => $this->session->userdata('id_user')
			);
			$this->db->insert('logs', $log);				

			redirect('admin');
		}
	}
?>