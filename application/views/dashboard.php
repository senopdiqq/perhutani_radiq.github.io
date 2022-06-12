<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>

<head>
	<title>Perum Perhutani - Koperasi Simpan Pinjam</title>
	<meta charset="utf-8">
	<meta name="theme-color" content="#222222">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="icon" href="asset/image/icon.png" type="image/png" sizes="32x32">
	<link rel="stylesheet" href="asset/css/style.css">
	<link rel="stylesheet" href="asset/font/css/all.css">
	<script src="asset/js/jquery-3.4.1.min.js"></script>
</head>

<body>
	<div class="header">
		<h3 class="home">KOPERASI SIMPAN PINJAM<br><span>Perum Perhutani KPH Pasuruan</span></h3>
		<label><?php echo ucwords($this->session->userdata('nama')); ?></label>
		<img id="logout" src="asset/image/admin.png">
	</div>
	<div onclick="window.location.href='./';" id="poplog" class="poplog">Log Out</div>
	<ul>
		<?php if ($this->session->userdata('user') == 'admin' || $this->session->userdata('user') == 'bendahara') { ?>
			<li class="none"><i class="fas fa-database"></i>M a s t e r</li>
		<?php } ?>
		<?php if ($this->session->userdata('user') == 'admin' || $this->session->userdata('user') == 'bendahara') { ?>
			<li class="lisub active" onclick="window.location.href='<?php echo site_url('dashboard') ?>';"><i class="fas fa-donate"></i>Dashboard</li>
		<?php } ?>
		<?php if ($this->session->userdata('user') == 'admin') { ?>

			<li class="lisub" onclick="window.location.href='<?php echo site_url('admin') ?>';"><i class="fas fa-user-shield"></i>U&nbsp;&nbsp;s&nbsp;&nbsp;e&nbsp;&nbsp;r</li>
		<?php }
		if ($this->session->userdata('user') == 'admin' || $this->session->userdata('user') == 'bendahara') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('simpanan') ?>';"><i class="fas fa-donate"></i>Simpanan</l>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pinjaman') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('anggota') ?>';"><i class="fas fa-users"></i>Anggota</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_pinjaman') ?>';"><i class="fas fa-table"></i>Laporan Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_simpanan') ?>';"><i class="fas fa-table"></i>Laporan Simpanan</li>
		<?php } ?>

		<?php if ($this->session->userdata('user') == 'bendahara' || $this->session->userdata('user') == 'karyawan') { ?>
			<li class="none"><i class="fas fa-cash-register"></i>Transaksi</li>
		<?php } ?>
		<?php if ($this->session->userdata('user') == 'karyawan') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('tabungan') ?>';"><i class="fas fa-donate"></i>Simpanan</li>

		<?php }
		if ($this->session->userdata('user') == 'bendahara' || $this->session->userdata('user') == 'karyawan') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pengajuan') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</>
			<?php } ?>

	</ul>
	<div class="content">
		<div class="col-12 left">
			<h1>Dashboard</h1>
		</div>
		<?php
		$sql1 = $this->db->get('anggota');
		$row1 = $sql1->num_rows();
		$sql2 = $this->db->get('tabungan');
		$row2 = $sql2->num_rows();
		$sql3 = $this->db->select('*')
			->from('pengajuan')
			->where('status', 'Ya')
			->get();
		$row3 = $sql3->num_rows();
		$sql4 = $this->db->select('*')
			->from('pengajuan')
			->where('status', 'Tidak')
			->get();
		$row4 = $sql4->num_rows();
		?>
		<div class="col-3">
			<div class="box">
				<i class="fas fa-users"></i>
				<h1><?php echo $row1 ?></h1>
				<h5>Anggota</h5>
			</div>
		</div>
		<div class="col-3">
			<div class="box">
				<i class="fas fa-donate"></i>
				<h1><?php echo $row2 ?></h1>
				<h5>Simpanan</h5>
			</div>
		</div>
		<div class="col-3">
			<div class="box">
				<i class="fas fa-credit-card"></i>
				<h1><?php echo $row3 ?></h1>
				<h5>Pinjaman</h5>
			</div>
		</div>
		<div class="col-3">
			<div class="box">
				<i class="fas fa-credit-card"></i>
				<h1><?php echo $row4 ?></h1>
				<h5>Pengajuan</h5>
			</div>
		</div>

	</div>
	</div>
	<label class="footpage">Radiq Arbi L - Brawijaya Univ. 2021</label>
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