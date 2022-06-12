<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Obat extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='apoteker' && $this->session->userdata('user')!='assistant')) {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('obat');
			}		
		}
		
		public function cari() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='apoteker' && $this->session->userdata('user')!='assistant')) {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$this->session->set_userdata('cari_obat', $this->input->post('nama'));
			$result = true;
			
			echo json_encode($result);
		}
		
		public function tambah() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='apoteker' && $this->session->userdata('user')!='assistant')) {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}
			
			$sql = $this->db->get_where('obat', array('nama' => $this->input->post('nama')));
			if ($sql->num_rows()>0) {
				$result = '0#Nama Sudah Digunakan#nama';				
			} else {
				$data = array(
					'nama' => $this->input->post('nama'),
					'satuan' => $this->input->post('satuan'),
					'jenis' => $this->input->post('jenis'),
					'harga' => $this->input->post('harga')
				);
				$this->db->insert('obat', $data);

				$src = "SELECT * FROM obat ORDER BY id_obat DESC LIMIT 0,1";
				$sql = $this->db->query($src);
				$row = $sql->row();

				$data = array(
					'id_obat' => $row->id_obat
				);
				$this->db->insert('obat_dtl', $data);

				$log = array(
					'action' => 'Tambah Data Obat',
					'user' => $this->session->userdata('id_user')
				);
				$this->db->insert('logs', $log);
				
				$result = '1#obat';
			}
		
			echo json_encode($result);
		}
		
		public function ubah() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='apoteker' && $this->session->userdata('user')!='assistant')) {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			if ($this->input->get('id')!=null) {
				$this->session->set_userdata('id_obat', $this->input->get('id'));
				redirect('obat?p=ubah');
			} else {
				$sql = $this->db->get_where('obat', array('nama' => $this->input->post('nama'),'id_obat!=' => $this->session->userdata('id_obat')));
				if ($sql->num_rows()>0) {
					$result = '0#Nama Sudah Digunakan#nama';
				} else {					
					$data = array(
						'nama' => $this->input->post('nama'),
						'satuan' => $this->input->post('satuan'),
						'jenis' => $this->input->post('jenis'),
						'harga' => $this->input->post('harga')
					);
					$this->db->where('id_obat', $this->session->userdata('id_obat'));
					$this->db->update('obat', $data);

					$log = array(
						'action' => 'Ubah Data Obat',
						'user' => $this->session->userdata('id_user')
					);
					$this->db->insert('logs', $log);				
				
					$result = '1#obat';
				}
			
				echo json_encode($result);			
			}
		}

		public function view() {
			if (empty($this->session->userdata('user')) || ($this->session->userdata('user')!='apoteker' && $this->session->userdata('user')!='assistant')) {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$this->session->set_userdata('id_obat', $this->input->get('id'));
			redirect('obat?p=view');			
		}

		public function hapus() {
			$this->db->delete('obat', array('id_obat' => $this->input->post('idpop')));
			$this->db->delete('obat_dtl', array('id_obat' => $this->input->post('idpop')));
			$this->db->delete('stok_dtl', array('id_obat' => $this->input->post('idpop')));
			$this->db->delete('hasil_dtl', array('id_obat' => $this->input->post('idpop')));
			
			$log = array(
				'action' => 'Hapus Data Obat',
				'user' => $this->session->userdata('id_user')
			);
			$this->db->insert('logs', $log);				

			redirect('obat');
		}
	}
?>