<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order extends CI_Controller {
		public function __construct() {
			parent::__construct();		
		}
		
		public function index() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='pengadaan') {
				$this->session->sess_destroy();
				redirect('home');
			}
			else {
				$this->load->view('order');
			}		
		}
		
		public function tambah() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='pengadaan') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$tgl = $this->input->post('thn') . '-' . $this->input->post('bln') . '-' . $this->input->post('tgl');
			$data = array(
				'tanggal' => $tgl,
				'nama' => $this->input->post('nama'),
				'catatan' => 'Masuk',
				'id_admin' => $this->session->userdata('id_user')
			);
			$this->db->insert('stok', $data);

			$log = array(
				'action' => 'Tambah Data Order',
				'user' => $this->session->userdata('id_user')
			);
			$this->db->insert('logs', $log);
				
			$result = '1#stok';

			echo json_encode($result);
		}
		
		public function item() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='pengadaan') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$jumlah = (int)$this->input->post('jumlah');

			$sql = $this->db->get_where('stok_dtl', array('id_stok' => $this->input->post('id_stok'),'id_obat' => $this->input->post('id_obat')));
			if ($sql->num_rows()>0) {
				$row = $sql->row();
				$jumlah = (int)$this->input->post('jumlah') + (int)$row->jumlah;

				/* STOK MASTER */
				$sqld = $this->db->get_where('obat_dtl', array('id_obat' => $this->input->post('id_obat')));
				$rowd = $sqld->row();

				$lama = (int)$rowd->sisa;
				$awal = (int)$rowd->sisa;
				$baru = $jumlah;
				$sisa = (int)$rowd->sisa + (int)$this->input->post('jumlah');

				$data = array(
					'lama' => $lama,
					'awal' => $awal,
					'baru' => $baru,
					'sisa' => $sisa				
				);
				$this->db->where('id_obat', $this->input->post('id_obat'));
				$this->db->update('obat_dtl', $data);
				/* STOK MASTER */				

				$this->db->delete('stok_dtl', array('id_stok' => $this->input->post('id_stok'),'id_obat' => $this->input->post('id_obat')));
			} else {
				/* STOK MASTER */
				$sqld = $this->db->get_where('obat_dtl', array('id_obat' => $this->input->post('id_obat')));
				$rowd = $sqld->row();

				$lama = (int)$rowd->sisa;
				$awal = (int)$rowd->sisa;
				$baru = $jumlah;
				$sisa = (int)$rowd->sisa + $jumlah;

				$data = array(
					'lama' => $lama,
					'awal' => $awal,
					'baru' => $baru,
					'sisa' => $sisa				
				);
				$this->db->where('id_obat', $this->input->post('id_obat'));
				$this->db->update('obat_dtl', $data);
				/* STOK MASTER */			
			}

			$data = array(
				'id_stok' => $this->input->post('id_stok'),
				'id_obat' => $this->input->post('id_obat'),
				'jumlah' => $jumlah
			);
			$this->db->insert('stok_dtl', $data);

			$result = '1#stok';

			echo json_encode($result);
		}

		public function hapusitem() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='pengadaan') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$sql = $this->db->get_where('stok_dtl', array('id_stok' => $_GET['id_stok'],'id_obat' => $_GET['id_obat']));
			$row = $sql->row();
			$jumlah = (int)$row->jumlah;

			/* STOK MASTER */
			$sqld = $this->db->get_where('obat_dtl', array('id_obat' => $_GET['id_obat']));
			$rowd = $sqld->row();

			$baru = (int)$rowd->baru - $jumlah;
			$sisa = (int)$rowd->sisa - $jumlah;

			$data = array(
				'baru' => $baru,
				'sisa' => $sisa				
			);
			$this->db->where('id_obat', $_GET['id_obat']);
			$this->db->update('obat_dtl', $data);
			/* STOK MASTER */			

			$this->db->delete('stok_dtl', array('id_stok' => $_GET['id_stok'],'id_obat' => $_GET['id_obat']));
			
			redirect('order?p=item');
		}
		
		public function viewitem() {
			if (empty($this->session->userdata('user')) || $this->session->userdata('user')!='pengadaan') {
				$this->session->sess_destroy();
				redirect('home');
				exit();
			}

			$this->session->set_userdata('id_stok', $this->input->get('id'));
			redirect('order?p=view');			
		}
	}
?>