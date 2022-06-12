<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dashboard extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='admin' && $this->session->userdata('user')!='bendahara' && $this->session->userdata('user')!='karyawan')) {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('dashboard');
			}		
		}
	}
?>