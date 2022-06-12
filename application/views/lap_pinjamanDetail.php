<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>

<head>
	<title>Perum Perhutani - Koperasi Simpan Pinjam</title>
	<meta charset="utf-8">
	<meta name="theme-color" content="#222222">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="icon" href="../asset/image/icon.png" type="image/png" sizes="32x32">
	<link rel="stylesheet" href="../asset/css/style.css">
	<link rel="stylesheet" href="../asset/font/css/all.css">
	<script src="../asset/js/jquery-3.4.1.min.js"></script>
</head>

<body>
	<div class="header">
		<h3 class="home">KOPERASI SIMPAN PINJAM<br><span>Perum Perhutani KPH Pasuruan</span></h3>
		<label><?php echo ucwords($this->session->userdata('nama')); ?></label>
		<img id="logout" src="../asset/image/admin.png">
	</div>
	<div onclick="window.location.href='../';" id="poplog" class="poplog">Log Out</div>
	<ul>
		<?php if ($this->session->userdata('user') == 'admin' || $this->session->userdata('user') == 'bendahara') { ?>
			<li class="none"><i class="fas fa-database"></i>M a s t e r</li>
		<?php } ?>
		<?php if ($this->session->userdata('user') == 'admin') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('dashboard') ?>';"><i class="fas fa-donate"></i>Dashboard</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('admin') ?>';"><i class="fas fa-user-shield"></i>U&nbsp;&nbsp;s&nbsp;&nbsp;e&nbsp;&nbsp;r</li>
		<?php }
		if ($this->session->userdata('user') == 'admin' || $this->session->userdata('user') == 'bendahara') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('simpanan') ?>';"><i class="fas fa-donate"></i>Simpanan</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pinjaman') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('anggota') ?>';"><i class="fas fa-users"></i>Anggota</li>
			<li class="lisub active" onclick="window.location.href='<?php echo site_url('lap_pinjaman') ?>';"><i class="fas fa-table"></i>Laporan Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_simpanan') ?>';"><i class="fas fa-table"></i>Laporan Simpanan</li>
		<?php } ?>

		<?php if ($this->session->userdata('user') == 'bendahara' || $this->session->userdata('user') == 'karyawan') { ?>
			<li class="none"><i class="fas fa-cash-register"></i>Transaksi</li>
		<?php } ?>
		<?php if ($this->session->userdata('user') == 'karyawan') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('tabungan') ?>';"><i class="fas fa-donate"></i>Simpanan</li>
		<?php }
		if ($this->session->userdata('user') == 'bendahara' || $this->session->userdata('user') == 'karyawan') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pengajuan') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
		<?php } ?>
	</ul>
	<div class="content">
		<div class="col-6 left">
			<h1>Laporan Pinjaman<b>'<?= $detail_nama ?>'</b> </h1>

		</div>
		<div class="col-6 right">
			<h1> <a onclick="printContent('print')" href="#">Print</a></b> </h1>
		</div>

		<div class="col-12">
			<div class="box shadow" id="print">
				<table table-striped table-bordered>
					<thead>
						<tr>
							<th>Tanggal</th>
							<th>Angsuran Ke</th>
							<th>Besar Angsurann</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						foreach ($detail as $det) {

							$bulan = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des');
							$tgl = date('d', strtotime($det->tanggal));
							$bln = $bulan[(int) date('m', strtotime($det->tanggal))];
							$thn = date('Y', strtotime($det->tanggal));
							$tbt = $tgl . ' ' . $bln . ' ' . $thn;

						?>
							<tr style="text-align: center;">
								<td><?= $tbt ?></td>
								<td><?= $i ?></td>
								<td><?= $det->jumlah ?></td>
							</tr>
						<?php $i++;
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<label class="footpage">Radiq Arbi L - Brawijaya Univ. 2021</label>
	<script>
		function printContent(el) {
			var restorepage = document.body.innerHTML;
			var printcontent = document.getElementById(el).innerHTML;
			document.body.innerHTML = printcontent;
			window.print();
			document.body.innerHTML = restorepage;
		}
	</script>
	<script>
		$('#logout').hover(function() {
			var msg = document.getElementById('poplog');
			msg.style.visibility = 'visible';

			setTimeout(function() {
				msg.style.visibility = 'hidden';
			}, 2000);
		});
	</script>
</body>

</html>