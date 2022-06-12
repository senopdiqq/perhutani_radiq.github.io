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
		<li class="active"><i class="fas fa-capsules"></i>Obat</li>
		<li onclick="window.location.href='<?php echo site_url('resep') ?>';"><i class="fas fa-sticky-note"></i>Resep</li>		
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
			<h1>Obat</h1>
			<?php if (!isset($_GET['p']) || $_GET['p']=='cari') { ?>
				<?php if ($this->session->userdata('user')=='apoteker') { ?>
				<button class="sub" onclick="window.location.href='<?php echo site_url('obat') ?>?p=tambah';">Tambah</button>
				<?php } ?>
			<?php } ?>
		</div>
		<div class="col-6 right">
			<?php if (!isset($_GET['p']) || $_GET['p']=='cari') { ?>
				<input class="search" type="text" id="tcari" placeholder="Masukkan nama" maxlength="160" />
				<i id="bcari" class="fas fa-search" title="Cari"></i>
			<?php } ?>
		</div>
		<div class="col-12">
			<div class="box shadow">
				<table>
					<?php
						$sat = array('','Botol','Box','Tube');
					?>
					<?php if (!isset($_GET['p']) || $_GET['p']=='cari') { ?>
						<thead>						
							<tr>
								<th colspan="2">Nama</th>
								<th width="15%">Satuan</th>
								<th width="20%">Harga (PPh 10%)</th>
								<th width="15%">Jml Sisa</th>
								<?php if ($this->session->userdata('user')=='apoteker') { ?>
									<th width="15%">Aksi</th>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
								$this->db->group_by('jenis');
								if (!isset($_GET['p'])) {
								
								} else {
									$this->db->like('nama', $this->session->userdata('cari_obat'), 'both');
								}								
								$sql = $this->db->get('obat');
                         		foreach ($sql->result() as $row) {									
							?>
							<tr class="jenis">
								<td colspan="6"><?php echo $row->jenis ?></td>
							</tr>
							<?php
								$this->db->order_by('obat.nama');
								$this->db->select('obat.id_obat as id_obat,obat.nama as nama,obat.satuan as satuan,obat.jenis as jenis,obat.harga as harga,obat_dtl.sisa as sisa');
								$this->db->from('obat');
								$this->db->join('obat_dtl', 'obat_dtl.id_obat=obat.id_obat', 'right');
								$this->db->where('jenis', $row->jenis);
								if (!isset($_GET['p'])) {
								
								} else {
									$this->db->like('nama', $this->session->userdata('cari_obat'), 'both');
								}								
								$sqld = $this->db->get();								
                         		foreach ($sqld->result() as $rowd) {									
							?>
							<tr>
								<td width="2.5%"></td>
								<td width="32.5%"><?php echo $rowd->nama ?></td>
								<td align="center"><?php echo $sat[(int)$rowd->satuan] ?></td>
								<td align="right"><?php echo number_format($rowd->harga,0,'.',',') ?></td>		
								<td align="center"><?php echo $rowd->sisa ?></td>
								<?php if ($this->session->userdata('user')=='apoteker') { ?>
								<td align="center">								
									<i onclick="window.location.href='<?php echo site_url('obat/ubah') ?>?id=<?php echo $rowd->id_obat ?>';" class="fas fa-pen" title="Ubah"></i>
									<i onclick="pophapus(<?php echo $rowd->id_obat ?>)" class="fas fa-eraser" title="Hapus"></i>
								</td>
								<?php } ?>
							</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					<?php } else { ?>
						<?php
							$id_obat = ''; $nama = ''; $satuan = 0; $jenis = ''; $harga = ''; $btn = 'Simpan';
							
							if ($_GET['p']=='ubah') {
								$sql = $this->db->get_where('obat', array('id_obat' => $this->session->userdata('id_obat')));
								$row = $sql->row();
								$id_obat = $row->id_obat;
								$nama = $row->nama;
								$satuan = (int)$row->satuan;								
								$jenis = $row->jenis;								
								$harga = $row->harga;
								$btn = 'Update';
							}
						?>                            
						<tbody class="nohover">
							<tr>
								<td width="15%">Nama</td>
								<td width="1%">:</td>
								<td>
									<input hidden id="id_obat" value="<?php echo $id_obat ?>" />
									<input type="text" id="nama" maxlength="160" value="<?php echo $nama ?>" />
								</td>
							</tr>
							<tr>
								<td>Satuan</td>
								<td>:</td>
								<td>
									<select id="satuan">
										<option selected value="0"></option>
										<?php for ($r=1; $r<=3; $r++) { if ($r==$satuan) { $sel = "selected"; } else { $sel = ""; } ?>
											<option <?php echo $sel ?> value="<?php echo $r ?>"><?php echo $sat[$r] ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Jenis</td>
								<td>:</td>
								<td>
									<input type="text" id="jenis" maxlength="160" value="<?php echo $jenis ?>" />
								</td>
							</tr>
							<tr>
								<td>Harga (PPh 10%)</td>
								<td>:</td>
								<td>
									<input type="text" id="harga" maxlength="7" value="<?php echo $harga ?>" />
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
									<button id="simpan" class="sub"><?php echo $btn ?></button>
									<button onclick="window.location.href='<?php echo site_url('obat') ?>';" class="sub cancel">Batal</button>
								</td>
							</tr>
						</tbody>
					<?php } ?>
				</table>				
			</div>			
		</div>		
	</div>
	<label class="footpage">Prediksi Stok Obat<br>Arik - Bhayangkara Univ 2020</label>
	<div id="popdel" class="popdel"></div>
	<div id="boxdel" class="boxdel">
		Hapus Data ?<br /><br /><br />
		<form method="post" action="obat/hapus">
			<input hidden id="idpop" name="idpop">
			<button type="submit" class="sub">Ya</button>
			<button type="reset" onclick="pophidden()" class="sub cancel">Tidak</button>
		</form>
	</div>		
	
	<script>
		$('#harga').on('keyup', function (event) {    
     		var charCode = event.keyCode;
     		if ((charCode<48 || charCode>57) && charCode!=8 && charCode!=229) {
     			$('#harga').val('');
     		}
     	})
		$('#logout').hover(function() {
			var msg = document.getElementById('poplog');
			msg.style.visibility = 'visible';

			setTimeout(function() {
				msg.style.visibility = 'hidden';
			}, 2000);
		});
		$('#bcari').click(function() {
			event.preventDefault();
               
			var nama = $('#tcari').val();
			
			if (nama=='') {
				$('#tcari').focus();				
				return false;
			}

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>obat/cari',
				dataType: 'json',
				data: { nama: nama },
				success: function(result) {
					window.location.href = '<?php echo base_url() ?>obat?p=cari';
				}
			});			
		});
		$('#simpan').click(function() {
			event.preventDefault();
               
			var id_obat = $('#id_obat').val();
			var nama = $('#nama').val();
			var satuan = $('#satuan').val();
			var jenis = $('#jenis').val();
			var harga = $('#harga').val();
			
			if (nama=='' || satuan=='0' || jenis=='' || harga=='') {
				if (nama=='') {
					$('#nama').focus();				
				}
				else if (satuan=='0') {
					$('#satuan').focus();				
				}
				else if (jenis=='') {
					$('#jenis').focus();				
				}
				else if (harga=='') {
					$('#harga').focus();				
				}
				return false;
			}

			$.ajax({
				type: 'post',
				url: (id_obat=='') ? '<?php echo base_url() ?>obat/tambah' : '<?php echo base_url() ?>obat/ubah',
				dataType: 'json',
				data: { nama: nama, satuan: satuan, jenis: jenis, harga: harga },
				success: function(result) {
					notified(result);						
				}
			});			
		});
		function notified(err) {
			var e = parseInt(err.split('#')[0]);
			var focus = err.split('#')[2];
			var msg = document.getElementById('notif');
			
			if (e==0) {
				msg.innerText = err.split('#')[1];
				setTimeout(function() {
					document.getElementById(focus).focus();
					msg.innerText = '';
				}, 2000);
			} else {
				window.location.href = '<?php echo base_url() ?>' + err.split('#')[1];
			}
		}
	</script>
</body>
</html>