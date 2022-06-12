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
			<li class="lisub active"><i class="fas fa-credit-card"></i>Pinjaman</li>
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
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pengajuan') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
		<?php } ?>
	</ul>
	<div class="content">
		<div class="col-6 left">
			<h1>Pinjaman</h1>
			<?php if (!isset($_GET['p'])) { ?>
				<button class="sub" onclick="window.location.href='<?php echo site_url('pinjaman') ?>?p=tambah';">Tambah</button>
			<?php } ?>
		</div>
		<div class="col-6 right">
		</div>
		<div class="col-12">
			<div class="box shadow">
				<table>
					<?php if (!isset($_GET['p'])) { ?>
						<thead>
							<tr>
								<th width="35%">Nama</th>
								<th colspan="2">Besar Rp.</th>
								<th colspan="2">Min Gaji Rp.</th>
								<th width="15%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$this->db->order_by('id_pinjaman asc');
							$sql = $this->db->get('pinjaman');
							foreach ($sql->result() as $row) {
							?>
								<tr>
									<td><?php echo $row->nama ?></td>
									<td width="20%" align="right"><?php echo number_format($row->maks, 0, '.', ',') ?></td>
									<td width="5%"></td>
									<td width="20%" align="right"><?php echo number_format($row->mins, 0, '.', ',') ?></td>
									<td width="5%"></td>
									<td align="center">
										<i onclick="window.location.href='<?php echo site_url('pinjaman/ubah') ?>?id=<?php echo $row->id_pinjaman ?>';" class="fas fa-pen" title="Ubah"></i>
										<i onclick="pophapus(<?php echo $row->id_pinjaman ?>)" class="fas fa-eraser" title="Hapus"></i>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					<?php } else { ?>
						<?php
						$id_pinjaman = '';
						$nama = '';
						$maks = '';
						$mins = '';
						$btn = 'Simpan';

						if ($_GET['p'] == 'ubah') {
							$sql = $this->db->get_where('pinjaman', array('id_pinjaman' => $this->session->userdata('id_pinjaman')));
							$row = $sql->row();
							$id_pinjaman = $row->id_pinjaman;
							$nama = $row->nama;
							$maks = $row->maks;
							$mins = $row->mins;
							$btn = 'Update';
						}
						?>
						<tbody class="nohover">
							<tr>
								<td>Nama</td>
								<td>:</td>
								<td>
									<input hidden id="id_pinjaman" value="<?php echo $id_pinjaman ?>" />
									<input type="text" id="nama" maxlength="160" value="<?php echo $nama ?>" />
								</td>
							</tr>
							<tr>
								<td>Besar Rp.</td>
								<td>:</td>
								<td><input type="text" id="maks" onkeyup="maks()" maxlength="8" value="<?php echo $maks ?>" /></td>
							</tr>
							<tr>
								<td>Min Gaji Rp.</td>
								<td>:</td>
								<td><input type="text" id="mins" onkeyup="mins()" maxlength="8" value="<?php echo $mins ?>" /></td>
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
									<button onclick="window.location.href='<?php echo site_url('pinjaman') ?>';" class="sub cancel">Batal</button>
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
		Hapus Data ?<br /><br /><br />
		<form method="post" action="pinjaman/hapus">
			<input hidden id="idpop" name="idpop">
			<button type="submit" class="sub">Ya</button>
			<button type="reset" onclick="pophidden()" class="sub cancel">Tidak</button>
		</form>
	</div>

	<script>
		function maks() {
			var n = document.getElementById('maks').value;

			if (n.length > 0 && ((n.charCodeAt(n.length - 1) < 48 || n.charCodeAt(n.length - 1) > 57) && n.charCodeAt(n.length - 1) != 8)) {
				document.getElementById('maks').value = '';
			}
			if (n.length > 1 && +document.getElementById('maks').value == 0) {
				document.getElementById('maks').value = '';
			}
			if (n.length < 2 && +document.getElementById('maks').value == 0) {
				document.getElementById('maks').value = '';
			}
		}

		function mins() {
			var n = document.getElementById('mins').value;

			if (n.length > 0 && ((n.charCodeAt(n.length - 1) < 48 || n.charCodeAt(n.length - 1) > 57) && n.charCodeAt(n.length - 1) != 8)) {
				document.getElementById('mins').value = '';
			}
			if (n.length > 1 && +document.getElementById('mins').value == 0) {
				document.getElementById('mins').value = '';
			}
			if (n.length < 2 && +document.getElementById('mins').value == 0) {
				document.getElementById('mins').value = '';
			}
		}
		$('#logout').hover(function() {
			var msg = document.getElementById('poplog');
			msg.style.visibility = 'visible';

			setTimeout(function() {
				msg.style.visibility = 'hidden';
			}, 2000);
		});
		$('#simpan').click(function() {
			event.preventDefault();

			var id_pinjaman = $('#id_pinjaman').val();
			var nama = $('#nama').val();
			var maks = $('#maks').val();
			var mins = $('#mins').val();

			if (nama == '' || maks == '' || mins == '') {
				if (nama == '') {
					$('#nama').focus();
				} else if (maks == '') {
					$('#maks').focus();
				} else if (mins == '') {
					$('#mins').focus();
				}
				return false;
			}

			$.ajax({
				type: 'post',
				url: (id_pinjaman == '') ? '<?php echo base_url() ?>pinjaman/tambah' : '<?php echo base_url() ?>pinjaman/ubah',
				dataType: 'json',
				data: {
					nama: nama,
					maks: maks,
					mins: mins
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