<?php defined('BASEPATH') OR exit('No direct script access allowed'); date_default_timezone_set("Asia/Jakarta"); ?>

<!DOCTYPE html>
<head>
	<title>Apotek | Klinik & Rumah Sakit Pusura</title>
    <meta charset="utf-8">
	<meta name="theme-color" content="#222222">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">    
    <link rel="icon" href="asset/image/icon.png">
    <link rel="stylesheet" href="asset/css/style.css">
	<link rel="stylesheet" href="asset/font/css/all.css">
	<script src="asset/js/jquery-3.4.1.min.js"></script>	
	<script src="asset/js/function.js"></script>	
</head>
<body>
	<div class="header">
		<h3 onclick="window.location.href='<?php echo site_url('dashboard') ?>';">KLINIK & RUMAH SAKIT<br><span>Pusura Rungkut Surabaya ID</span></h3>
		<label><?php echo ucwords($this->session->userdata('nama')); ?></label>
		<img id="logout" src="asset/image/admin.png">		
	</div>
	<div onclick="window.location.href='./';" id="poplog" class="poplog">Log Out</div>
	<ul>
		<?php if ($this->session->userdata('user')=='admin') { ?>
		<li onclick="window.location.href='<?php echo site_url('admin') ?>';"><i class="fas fa-user-shield"></i>Admin</li>
		<?php } ?>
		<?php if ($this->session->userdata('user')=='apoteker' || $this->session->userdata('user')=='assistant') { ?>
		<li onclick="window.location.href='<?php echo site_url('obat') ?>';"><i class="fas fa-capsules"></i>Obat</li>
		<li class="active"><i class="fas fa-sticky-note"></i>Resep</li>		
		<?php } ?>
		<?php if ($this->session->userdata('user')=='pengadaan') { ?>
		<li onclick="window.location.href='<?php echo site_url('order') ?>';"><i class="fas fa-shopping-cart"></i>Order</li>
		<li onclick="window.location.href='<?php echo site_url('retur') ?>';"><i class="fas fa-undo"></i>Retur</li>
		<li onclick="window.location.href='<?php echo site_url('method') ?>';"><i class="fas fa-square-root-alt"></i>F - DES</li>
		<li onclick="window.location.href='<?php echo site_url('hasil') ?>';"><i class="fas fa-file-alt"></i>Hasil</li>
		<?php } ?>
	</ul>
	<div class="content">
		<div class="col-6 left">
			<h1>Resep</h1>
			<?php if (!isset($_GET['p']) || $_GET['p']=='cari') { ?>
				<?php if ($this->session->userdata('user')=='apoteker') { ?>
				<button class="sub" onclick="window.location.href='<?php echo site_url('resep') ?>?p=tambah';">Tambah</button>
				<?php } ?>
			<?php } ?>
		</div>
		<div class="col-6 right">
		</div>
		<div class="col-12">
			<div class="box shadow">
					<?php
						$sat = array('','Botol','Box','Tube');
						$bulan = array('','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nop','Des');
					?>
					<?php if (!isset($_GET['p']) || $_GET['p']=='cari') { ?>
					<table>
						<thead>						
							<tr>
								<th width="25%">Tanggal</th>								
								<th width="40%">Pasien</th>								
								<th width="20%">Umur (Th)</th>								
								<th width="15%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$this->db->order_by('stok.tanggal desc','stok.id_stok desc');
								$this->db->select('stok.tanggal as tanggal,stok.nama as nama,stok.catatan as catatan,resep.umur as umur,stok.id_stok as id_stok');
								$this->db->from('resep');
								$this->db->join('stok', 'stok.id_stok=resep.id_stok', 'left');
								$this->db->where('stok.catatan', 'Keluar');
								$sql = $this->db->get();								
                         		foreach ($sql->result() as $row) {
                         			$tgl = date('d', strtotime($row->tanggal));
                         			$bln = $bulan[(int)date('m', strtotime($row->tanggal))];
                         			$thn = date('Y', strtotime($row->tanggal));
                         			$tbt = $tgl . ' ' . $bln . ' ' . $thn;
                         			$srcd = "SELECT * FROM stok_dtl WHERE id_stok = " . $row->id_stok . "";
                         			$sqld = $this->db->query($srcd);
                         			if ($sqld->num_rows()>0) {
							?>
							<tr>
								<td align="center"><?php echo $tbt ?></td>
								<td><?php echo $row->nama ?></td>
								<td align="center"><?php echo $row->umur ?></td>
								<td align="center">
									<i onclick="window.location.href='<?php echo site_url('resep/viewitem') ?>?id=<?php echo $row->id_stok ?>';" class="fas fa-eye" title="Lihat"></i>
								</td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
					<?php } else { ?>
						<?php if ($_GET['p']=='tambah') { ?>
					<table>
						<tbody class="nohover">
							<tr>
								<td>Tanggal</td>
								<td>:</td>
								<td>
									<select id="tgl" class="date">
										<option selected value="0"></option>
										<?php for ($r=1; $r<=31; $r++) { if ($r<10) { $tanggal = '0' . $r; } else { $tanggal = $r; } ?>
											<option value="<?php echo $r ?>"><?php echo $tanggal ?></option>
										<?php } ?>
									</select>
									<select id="bln" class="date">
										<option selected value="0"></option>
										<?php for ($r=1; $r<=12; $r++) { ?>
											<option value="<?php echo $r ?>"><?php echo $bulan[$r] ?></option>
										<?php } ?>
									</select>
									<select id="thn" class="date">
										<option selected value="0"></option>
										<?php for ($r=(int)date('Y'); $r>=(int)date('Y')-1; $r--) { ?>
											<option value="<?php echo $r ?>"><?php echo $r ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td width="15%">Pasien</td>
								<td width="1%">:</td>
								<td>
									<input type="text" id="nama" maxlength="160" />
								</td>
							</tr>							
							<tr>
								<td>Umur (Th)</td>
								<td>:</td>
								<td>
									<input type="text" id="umur" maxlength="2" />
								</td>
							</tr>							
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td>
									<input type="text" id="alamat" maxlength="160" />
								</td>
							</tr>							
							<tr>
								<td>Kota</td>
								<td>:</td>
								<td>
									<input type="text" id="kota" maxlength="160" />
								</td>
							</tr>							
							<tr>
								<td></td>
								<td></td>
								<td><label class="notifpage" id="notif"></label></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td>
									<button id="lanjut" class="sub">Lanjut</button>
									<button onclick="window.location.href='<?php echo site_url('resep') ?>';" class="sub cancel">Batal</button>
								</td>
							</tr>
						</tbody>
					</table>
						<?php } else if ($_GET['p']=='item') { ?>
						<?php
							$src = "SELECT * FROM stok ORDER BY id_stok DESC LIMIT 0,1";
                         	$sql = $this->db->query($src);
                         	$row = $sql->row();
                         	$id_stok = $row->id_stok;
                         	$tgl = date('d', strtotime($row->tanggal));
                         	$bln = $bulan[(int)date('m', strtotime($row->tanggal))];
                         	$thn = date('Y', strtotime($row->tanggal));
                         	$tbt = $tgl . ' ' . $bln . ' ' . $thn;

                         	$sqld = $this->db->get_where('resep', array('id_stok' => $id_stok));
                         	$rowd = $sqld->row();
						?>
					<table>
						<tbody class="nohover">
							<tr>
								<td>Tanggal</td>
								<td>:</td>
								<td colspan="4">
									<input hidden id="id_stok" value="<?php echo $id_stok ?>" />
									<input type="text" readonly value="<?php echo $tbt ?>" />
								</td>								
							</tr>
							<tr>
								<td>Pasien</td>
								<td>:</td>
								<td colspan="4">
									<input type="text" readonly value="<?php echo $row->nama ?>" />
								</td>								
							</tr>							
							<tr>
								<td>Umur (Th)</td>
								<td>:</td>
								<td colspan="4">
									<input type="text" readonly value="<?php echo $rowd->umur ?>" />
								</td>								
							</tr>
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td colspan="4">
									<input type="text" readonly value="<?php echo $rowd->alamat ?>" />
								</td>								
							</tr>
							<tr>
								<td>Kota</td>
								<td>:</td>
								<td colspan="4">
									<input type="text" readonly value="<?php echo $rowd->kota ?>" />
								</td>								
							</tr>
							<tr>
								<td width="15%">Obat</td>
								<td width="1%">:</td>
								<td width="42%">
									<select id="id_obat">
										<option selected value="0"></option>
										<?php
											$this->db->order_by('obat.nama asc');
											$this->db->select('obat.id_obat as id_obat,obat.nama as nama,obat_dtl.sisa as sisa');
											$this->db->from('obat');
											$this->db->join('obat_dtl', 'obat_dtl.id_obat=obat.id_obat', 'right');
											$this->db->where('obat_dtl.sisa>', '0');
											$sql = $this->db->get();                         		
											foreach ($sql->result() as $row) {												
										?>
											<option value="<?php echo $row->id_obat ?>"><?php echo $row->nama ?></option>
										<?php } ?>
									</select>
								</td>
								<td width="42%"></td>
							</tr>
							<tr>
								<td>Qty</td>
								<td>:</td>
								<td>
									<input type="text" id="jumlah" maxlength="3" />
								</td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td>
									<button id="tambah" class="sub">Tambah</button>
									<button onclick="window.location.href='<?php echo site_url('resep') ?>';" class="sub proses">Proses</button>
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><label class="notifpage" id="notif"></label></td>
								<td></td>
							</tr>							
						</tbody>
					</table>
					<table>
						<thead>						
							<tr>
								<th width="35%">Nama</th>								
								<th width="30%">Satuan</th>								
								<th width="20%">Qty</th>
								<th width="15%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$this->db->order_by('stok_dtl.urut asc');
								$this->db->select('obat.nama as nama,obat.satuan as satuan,stok_dtl.jumlah as jumlah,stok_dtl.urut as urut,stok.id_stok as id_stok,stok_dtl.id_obat as id_obat');
								$this->db->from('stok_dtl');
								$this->db->join('obat', 'obat.id_obat=stok_dtl.id_obat', 'left');
								$this->db->join('stok', 'stok.id_stok=stok_dtl.id_stok', 'left');
								$this->db->where('stok.id_stok', $id_stok);
								$sql = $this->db->get();
								foreach ($sql->result() as $row) {
							?>
							<tr>
								<td><?php echo $row->nama ?></td>
								<td align="center"><?php echo $sat[(int)$row->satuan] ?></td>
								<td align="center"><?php echo $row->jumlah ?></td>
								<td align="center">
									<i onclick="window.location.href='<?php echo site_url('resep/hapusitem') ?>?id_stok=<?php echo $row->id_stok ?>&id_obat=<?php echo $row->id_obat ?>';" class="fas fa-eraser" title="Hapus"></i>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } else if ($_GET['p']=='view') { ?>
						<?php
							$sql = $this->db->get_where('stok', array('id_stok' => $this->session->userdata('id_stok')));
							$row = $sql->row();							
							$id_stok = $row->id_stok;
                         	$tgl = date('d', strtotime($row->tanggal));
                         	$bln = $bulan[(int)date('m', strtotime($row->tanggal))];
                         	$thn = date('Y', strtotime($row->tanggal));
                         	$tbt = $tgl . ' ' . $bln . ' ' . $thn;
						 	
						 	$sqld = $this->db->get_where('resep', array('id_stok' => $id_stok));
                         	$rowd = $sqld->row();
						?>
					<table>
						<tbody class="nohover">
							<tr>
								<td width="15%">Tanggal</td>
								<td width="1%">:</td>
								<td>
									<input type="text" readonly value="<?php echo $tbt ?>" />
								</td>								
							</tr>
							<tr>
								<td>Pasien</td>
								<td>:</td>
								<td>
									<input type="text" readonly value="<?php echo $row->nama ?>" />
								</td>								
							</tr>							
							<tr>
								<td>Umur (Th)</td>
								<td>:</td>
								<td>
									<input type="text" readonly value="<?php echo $rowd->umur ?>" />
								</td>								
							</tr>
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td>
									<input type="text" readonly value="<?php echo $rowd->alamat ?>" />
								</td>								
							</tr>
							<tr>
								<td>Kota</td>
								<td>:</td>
								<td>
									<input type="text" readonly value="<?php echo $rowd->kota ?>" />
								</td>								
							</tr>							
							<tr>
								<td></td>
								<td></td>
								<td>
									<button onclick="window.location.href='<?php echo site_url('resep') ?>';" class="sub cancel">Kembali</button>
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td><label class="notifpage" id="notif"></label></td>
								<td></td>
							</tr>							
						</tbody>
					</table>
					<table>
						<thead>						
							<tr>
								<th width="50%">Nama</th>								
								<th width="30%">Satuan</th>								
								<th width="20%">Qty</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$this->db->order_by('stok_dtl.urut asc');
								$this->db->select('obat.nama as nama,obat.satuan as satuan,stok_dtl.jumlah as jumlah,stok_dtl.urut as urut,stok.id_stok as id_stok,stok_dtl.id_obat as id_obat');
								$this->db->from('stok_dtl');
								$this->db->join('obat', 'obat.id_obat=stok_dtl.id_obat', 'left');
								$this->db->join('stok', 'stok.id_stok=stok_dtl.id_stok', 'left');
								$this->db->where('stok.id_stok', $id_stok);
								$sql = $this->db->get();
								foreach ($sql->result() as $row) {
							?>
							<tr>
								<td><?php echo $row->nama ?></td>
								<td align="center"><?php echo $sat[(int)$row->satuan] ?></td>
								<td align="center"><?php echo $row->jumlah ?></td>								
							</tr>
							<?php } ?>
						</tbody>
					</table>
						<?php } ?>
					<?php } ?>				
			</div>			
		</div>		
	</div>
	<label class="footpage">Prediksi Stok Obat<br>Arik - Bhayangkara Univ 2020</label>
	
	<script>
		$('#umur').on('keyup', function (event) {    
     		var charCode = event.keyCode;
     		if ((charCode<48 || charCode>57) && charCode!=8 && charCode!=229) {
     			$('#umur').val('');
     		}
     	})
		$('#jumlah').on('keyup', function (event) {    
     		var charCode = event.keyCode;
     		if ((charCode<48 || charCode>57) && charCode!=8 && charCode!=229) {
     			$('#jumlah').val('');
     		}
     	})
		$('#logout').hover(function() {
			var msg = document.getElementById('poplog');
			msg.style.visibility = 'visible';

			setTimeout(function() {
				msg.style.visibility = 'hidden';
			}, 2000);
		});
		$('#lanjut').click(function() {
			event.preventDefault();
               
			var tgl = $('#tgl').val();
			var bln = $('#bln').val();
			var thn = $('#thn').val();
			var nama = $('#nama').val();
			var umur = $('#umur').val();
			var alamat = $('#alamat').val();
			var kota = $('#kota').val();
			
			if (tgl=='0' || bln=='0' || thn=='0' || nama=='' || umur=='' || alamat=='' || kota=='') {
				if (tgl=='0') {
					$('#tgl').focus();				
				}
				else if (bln=='0') {
					$('#bln').focus();				
				}
				else if (thn=='0') {
					$('#thn').focus();				
				}
				else if (nama=='') {
					$('#nama').focus();				
				}
				else if (umur=='') {
					$('#umur').focus();				
				}
				else if (alamat=='') {
					$('#alamat').focus();				
				}
				else if (kota=='') {
					$('#kota').focus();				
				}
				return false;
			}

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>resep/tambah',
				dataType: 'json',
				data: { tgl: tgl, bln: bln, thn: thn, nama: nama, umur: umur, alamat: alamat, kota: kota },
				success: function(result) {
					window.location.href = '<?php echo base_url() ?>resep?p=item';
				}
			});			
		});
		$('#tambah').click(function() {
			event.preventDefault();
               
			var id_stok = $('#id_stok').val();
			var id_obat = $('#id_obat').val();
			var jumlah = $('#jumlah').val();
			
			if (id_obat=='0' || jumlah=='') {
				if (id_obat=='0') {
					$('#id_obat').focus();				
				}
				else if (jumlah=='') {
					$('#jumlah').focus();				
				}
				return false;
			}

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>resep/item',
				dataType: 'json',
				data: { id_stok: id_stok, id_obat: id_obat, jumlah: jumlah },
				success: function(result) {
					window.location.href = '<?php echo base_url() ?>resep?p=item';
				}
			});			
		});
	</script>
</body>
</html>