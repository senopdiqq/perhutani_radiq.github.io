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
	<script src="asset/js/function.js"></script>
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
			<li class="lisub" onclick="window.location.href='<?php echo site_url('dashboard') ?>';"><i class="fas fa-donate"></i>Dashboard</li>
		<?php } ?>
		<?php if ($this->session->userdata('user') == 'admin') { ?>
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
			<li class="lisub" onclick="window.location.href='<?php echo site_url('tarik_tunai') ?>';"><i class="fas fa-money-bill"></i>Ambil Tabungan</li>
			<li class="lisub active" onclick="window.location.href='<?php echo site_url('lap_pinjaman') ?>';"><i class="fas fa-table"></i>Laporan Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_simpanan') ?>';"><i class="fas fa-table"></i>Laporan Simpanan</li>
		<?php } ?>
	</ul>
	<div class="content">
		<div class="col-12 left">
			<h1>Laporan Pinjaman</h1>
		</div>

		<div class="col-12">
			<div class="box shadow">
				<table table-striped table-bordered>
					<thead>
						<tr>
							<th>Nama</th>
							<th width="15%">Tanggal Pinjam</th>
							<th width="10%">Tanggal Tempo</th>
							<th width="5%">Jenis Pinjaman</th>
							<th>Besar Pinjaman</th>
							<th width="5%">Lama Angsuran</th>
							<th>Status</th>
							<th width="10%">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pinjaman as $pin) {
							$cekhapus = $pin->sisa;
							$bulan = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des');
							$tgl = date('d', strtotime($pin->tanggal));
							$bln = $bulan[(int) date('m', strtotime($pin->tanggal))];
							$thn = date('Y', strtotime($pin->tanggal));
							$tbt = $tgl . ' ' . $bln . ' ' . $thn;

							$tempo = (int) date('m', strtotime($pin->tanggal)) + $pin->tenor;

							if ($tempo > 12) {
								$tempo2 = $tempo - 12;
								$bln2 = $bulan[$tempo2];
								$thn2 = $thn + 1;
								$tbt_tempo = $tgl . ' ' . $bln2 . ' ' . $thn2;
							} else {
								$bln2 = $bulan[$tempo];
								$tbt_tempo = $tgl . ' ' . $bln2 . ' ' . $thn;
							}

							if ($pin->sisa == 0) {
								$status = "Lunas";
							} else {
								$status = "Belum Lunas";
							}
						?>
							<tr style="text-align: center;">
								<td><?= $pin->nama_anggota ?></td>
								<td><?= $tbt ?></td>
								<td><?= $tbt_tempo ?></td>
								<td><?= $pin->nama_pinjaman ?></td>
								<td>Rp. <?= number_format($pin->jumlah) ?></td>
								<td><?= $pin->tenor ?> Bulan</td>
								<td><?= $status ?></td>
								<td>
									<i onclick="window.location.href='<?php echo site_url('lap_pinjaman/detail') ?>?id=<?php echo $pin->id_pengajuan ?>';" class="fas fa-eye" title="Check"></i>
									<?php if ($pin->sisa == 0) { ?>
										<i onclick="pophapus(<?php echo $pin->id_anggota ?>)" class="fas fa-eraser" title="Hapus"></i>

									<?php } else { ?>
										<i id="utang" style="color:red" class="fas fa-eraser" title="Hapus"></i>

									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<label class="footpage">Radiq Arbi L - Brawijaya Univ. 2021</label>
	<div id="popdel" class="popdel"></div>
	<div id="boxdel" class="boxdel">
		Hapus Data ?
		<br /><br /><br />
		<form method="post" action="lap_pinjaman/hapus">
			<input hidden id="idpop" name="idpop">
			<button type="submit" class="sub">Ya</button>
			<button type="reset" onclick="pophidden()" class="sub cancel">Tidak</button>
		</form>
	</div>

	<script>
		$('#logout').hover(function() {
			var msg = document.getElementById('poplog');
			msg.style.visibility = 'visible';

			setTimeout(function() {
				msg.style.visibility = 'hidden';
			}, 2000);
		});

		$('#utang').on('click', function() {
			alert('Pinjaman belum lunas');
		});
	</script>
</body>

</html>