<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Login extends CI_Controller {
		public function __construct() {
			parent::__construct();
		}
		
		public function in() {
			if ($this->input->post('user')=='admin' && $this->input->post('pass')=='admin') {
				$this->session->set_userdata('id_user', '0');
				$this->session->set_userdata('user', 'admin');
				$this->session->set_userdata('nama', 'Admin');
				$result = '1#dashboard';
			} else {
				$sql = $this->db->get_where('admin', array('user' => $this->input->post('user'),'pass' => MD5($this->input->post('pass'))));
				if ($sql->num_rows()>0) {
					$row = $sql->row();
					$this->session->set_userdata('id_user', $row->id_admin);
					$this->session->set_userdata('user', strtolower($row->stts));
					$this->session->set_userdata('nama', $row->user);
					$result = '1#dashboard';
				} else {
					$result = '0#Username Atau Password Salah';
				}
			}
			
			echo json_encode($result);
		}
	}
?>