<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>

<head>
	<title>Perum Perhutani - Koperasi Simpan Pinjam</title>
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
		<h3 onclick="window.location.href='<?php echo site_url('dashboard') ?>';">KOPERASI SIMPAN PINJAM<br><span>Perum Perhutani KPH Pasuruan</span></h3>
		<label><?php echo ucwords($this->session->userdata('nama')); ?></label>
		<img id="logout" src="asset/image/admin.png">
	</div>
	<div onclick="window.location.href='./';" id="poplog" class="poplog">Log Out</div>
	<ul>
		<li class="none"><i class="fas fa-cash-register"></i>Transaksi</li>
		<?php if ($this->session->userdata('user') == 'karyawan') { ?>
			<li class="lisub active" onclick="window.location.href='<?php echo site_url('tabungan') ?>';"><i class="fas fa-donate"></i>Simpanan</li>
		<?php }
		if ($this->session->userdata('user') == 'bendahara' || $this->session->userdata('user') == 'karyawan') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pengajuan') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
		<?php } ?>
		<?php if ($this->session->userdata('user') == 'karyawan') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('tarik_tunai') ?>';"><i class="fas fa-money-bill"></i>Ambil Tabungan</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_pinjaman') ?>';"><i class="fas fa-table"></i>Laporan Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_simpanan') ?>';"><i class="fas fa-table"></i>Laporan Simpanan</li>
		<?php } ?>
	</ul>
	<div class="content">
		<div class="col-6 left">
			<h1>Simpanan</h1>
			<?php if (!isset($_GET['p']) || $_GET['p'] == 'cari') { ?>
				<button class="sub" onclick="window.location.href='<?php echo site_url('tabungan') ?>?p=tambah';">Tambah</button>
			<?php } ?>
		</div>
		<div class="col-6 right">
			<?php if (!isset($_GET['p']) || $_GET['p'] == 'cari') { ?>
				<input class="search" type="text" id="tcari" placeholder="Masukkan nama" maxlength="160" />
				<i id="bcari" class="fas fa-search" title="Cari"></i>
			<?php } ?>
		</div>
		<div class="col-12">
			<div class="box shadow">
				<?php
				$bulan = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des');
				?>
				<table>
					<?php if (!isset($_GET['p']) || $_GET['p'] == 'cari') { ?>
						<thead>
							<tr>
								<th width="25%">Tanggal</th>
								<th width="35%">Nama Lengkap</th>
								<th width="20%">Keterangan</th>
								<th width="20%">Jumlah Rp.</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$this->db->order_by('list_tabungan.id_list desc');
							$this->db->select('list_tabungan.tanggal as tanggal,anggota.nama as anggota,simpanan.nama as simpanan,list_tabungan.jumlah as jumlah');
							$this->db->from('list_tabungan');
							$this->db->join('simpanan', 'simpanan.id_simpanan=list_tabungan.id_simpanan', 'left');
							$this->db->join('anggota', 'anggota.id_anggota=list_tabungan.id_anggota', 'left');
							if (!isset($_GET['p'])) {
							} else {
								$this->db->like('anggota.nama', $this->session->userdata('cari_tabungan'), 'both');
							}
							$sql = $this->db->get();
							foreach ($sql->result() as $row) {
								$tgl = date('d', strtotime($row->tanggal));
								$bln = $bulan[(int) date('m', strtotime($row->tanggal))];
								$thn = date('Y', strtotime($row->tanggal));
								$tbt = $tgl . ' ' . $bln . ' ' . $thn;
							?>
								<tr>
									<td align="center"><?php echo $tbt ?></td>
									<td align="center"><?php echo $row->anggota ?></td>
									<td align="center"><?php echo $row->simpanan ?></td>
									<td align="center"><?php echo number_format($row->jumlah, 0, '.', ',') ?></td>
								</tr>
							<?php } ?>
						</tbody>
					<?php } else { ?>
						<tbody class="nohover">
							<tr>
								<td>Tanggal</td>
								<td>:</td>
								<td>
									<select id="tgl" class="date">
										<option selected value="0"></option>
										<?php for ($r = 1; $r <= 31; $r++) {
											if ($r < 10) {
												$tanggal = '0' . $r;
											} else {
												$tanggal = $r;
											} ?>
											<option value="<?php echo $tanggal ?>"><?php echo $tanggal ?></option>
										<?php } ?>
									</select>
									<select id="bln" class="date">
										<option selected value="0"></option>
										<?php for ($r = 1; $r <= 12; $r++) {
											if ($r < 10) {
												$bln = '0' . $r;
											} else {
												$bln = $r;
											} ?>
											<option value="<?php echo $bln ?>"><?php echo $bulan[$r] ?></option>
										<?php } ?>
									</select>
									<select id="thn" class="date">
										<option selected value="0"></option>
										<?php for ($r = (int) date('Y'); $r >= (int) date('Y') - 1; $r--) { ?>
											<option value="<?php echo $r ?>"><?php echo $r ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Anggota</td>
								<td>:</td>
								<td>
									<select id="anggota">
										<option selected value="0"></option>
										<?php
										$this->db->order_by('nama asc');
										$sql = $this->db->get('anggota');
										foreach ($sql->result() as $row) {
										?>
											<option value="<?php echo $row->id_anggota ?>"><?php echo $row->nama; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td width="15%">Jenis</td>
								<td width="1%">:</td>
								<td>
									<select id="simpanan">
										<option selected value="0"></option>
										<?php
										$this->db->order_by('id_simpanan asc');
										$this->db->not_like('nama', 'pokok');
										$sql = $this->db->get('simpanan');
										foreach ($sql->result() as $row) {
										?>
											<option value="<?php echo $row->id_simpanan ?>|<?php echo $row->besar ?>"><?php echo $row->nama; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Besar Rp.</td>
								<td>:</td>
								<td><input type="text" id="besar" onkeyup="besar()" maxlength="8" value="" /></td>
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
									<button id="simpan" class="sub">Proses</button>
									<button onclick="window.location.href='<?php echo site_url('tabungan') ?>';" class="sub cancel">Batal</button>
								</td>
							</tr>
						</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
	<label class="footpage">Radiq Arbi L - Brawijaya Univ. 2021</label>
	<div id="popdel" class="popdel"></div>
	<div id="boxdel" class="boxdel">
		Lanjutkan Transaksi ?<br /><br /><br />
		<button id="proses" class="sub">Ya</button>
		<button type="reset" onclick="pophidden()" class="sub cancel">Tidak</button>
	</div>

	<script>
		function besar() {
			var n = document.getElementById('besar').value;

			if (n.length > 0 && ((n.charCodeAt(n.length - 1) < 48 || n.charCodeAt(n.length - 1) > 57) && n.charCodeAt(n.length - 1) != 8)) {
				document.getElementById('besar').value = '';
			}
			if (n.length > 1 && +document.getElementById('besar').value == 0) {
				document.getElementById('besar').value = '';
			}
			if (n.length < 2 && +document.getElementById('besar').value == 0) {
				document.getElementById('besar').value = '';
			}
		}
		$('#simpanan').change(function() {
			var jml = +document.getElementById('simpanan').value.split('|')[1];
			if (jml > 0) {
				document.getElementById('besar').disabled = true;
				document.getElementById('besar').value = jml;
			} else {
				document.getElementById('besar').disabled = false;
				document.getElementById('besar').value = '';
			}
		});
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

			if (nama == '') {
				$('#tcari').focus();
				return false;
			}

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>tabungan/cari',
				dataType: 'json',
				data: {
					nama: nama
				},
				success: function(result) {
					window.location.href = '<?php echo base_url() ?>tabungan?p=cari';
				}
			});
		});
		$('#simpan').click(function() {
			event.preventDefault();

			var tgl = $('#tgl').val();
			var bln = $('#bln').val();
			var thn = $('#thn').val();
			var anggota = $('#anggota').val();
			var simpanan = $('#simpanan').val();
			var besar = $('#besar').val();

			if (tgl == '0' || bln == '0' || thn == '0' || anggota == '0' || simpanan == '0' || besar == '') {
				if (tgl == '0') {
					$('#tgl').focus();
				} else if (bln == '0') {
					$('#bln').focus();
				} else if (thn == '0') {
					$('#thn').focus();
				} else if (anggota == '0') {
					$('#anggota').focus();
				} else if (simpanan == '0') {
					$('#simpanan').focus();
				} else if (besar == '') {
					$('#besar').focus();
				}
				return false;
			}

			var pop1 = document.getElementById('popdel');
			var pop2 = document.getElementById('boxdel');
			pop1.style.visibility = 'visible';
			pop2.style.visibility = 'visible';
			pop2.style.top = '35%';
			pop1.style.transition = '1s';
			pop2.style.transition = '0.5s';
		});
		$('#proses').click(function() {
			event.preventDefault();

			var tgl = $('#tgl').val();
			var bln = $('#bln').val();
			var thn = $('#thn').val();
			var anggota = $('#anggota').val();
			var simpanan = $('#simpanan').val();
			var besar = $('#besar').val();

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>tabungan/proses',
				dataType: 'json',
				data: {
					tgl: tgl,
					bln: bln,
					thn: thn,
					anggota: anggota,
					simpanan: simpanan,
					besar: besar
				},
				success: function(result) {
					notified(result);
				}
			});
		});

		function notified(err) {
			var e = parseInt(err.split('#')[0]);
			var focus = err.split('#')[2];
			var msg = document.getElementById('notif');

			if (e == 0) {
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