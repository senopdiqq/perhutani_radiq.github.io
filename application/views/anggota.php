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
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pinjaman') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>

			<li class="lisub active" onclick="window.location.href='<?php echo site_url('anggota') ?>';"><i class="fas fa-users"></i> Anggota</li>
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
			<h1>Anggota</h1>
			<?php if (!isset($_GET['p']) || $_GET['p'] == 'cari') { ?>
				<button class="sub" onclick="window.location.href='<?php echo site_url('anggota') ?>?p=tambah';">Tambah</button>
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
				<table>
					<?php
					$golongan1 = array('', 'IV', 'III', 'II');
					$golongan2 = array('', 'A', 'B', 'C', 'D');
					?>
					<?php if (!isset($_GET['p']) || $_GET['p'] == 'cari') { ?>
						<thead>
							<tr>
								<th width="20%">N I P</th>
								<th width="35%">Nama Lengkap</th>
								<th width="30%">Pangkat</th>
								<th width="15%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$this->db->order_by('id_anggota asc');
							if (!isset($_GET['p'])) {
							} else {
								$this->db->like('nama', $this->session->userdata('cari_anggota'), 'both');
							}
							$sql = $this->db->select('*')
								->from('anggota')
								->get()->result();
							foreach ($sql as $row) {
								if ((int) $row->nip > 0) {
									$nip = $row->nip;
								} else {
									$nip = '';
								}
							?>
								<tr>
									<td align="center"><?php echo $nip ?></td>
									<td align="center"><?php echo $row->nama ?></td>
									<td align="center"><?php echo $row->jab ?></td>
									<td align="center">
										<i onclick="window.location.href='<?php echo site_url('anggota/ubah') ?>?id=<?php echo $row->id_anggota ?>';" class="fas fa-pen" title="Ubah"></i>

										<i onclick="pophapus(<?php echo $row->id_anggota ?>)" class="fas fa-eraser" title="Hapus"></i>


									</td>
								</tr>
							<?php } ?>
						</tbody>
					<?php } else { ?>
						<?php
						$id_anggota = '';
						$nip = '';
						$nama = '';
						$gol1 = '';
						$gol2 = '';
						$jab = '';
						$upah = '';
						$visibility = '';
						$btn = 'Simpan';

						if ($_GET['p'] == 'ubah') {
							$sql = $this->db->get_where('anggota', array('id_anggota' => $this->session->userdata('id_anggota')));
							$row = $sql->row();
							$id_anggota = $row->id_anggota;
							if ((int) $row->nip > 0) {
								$nip = $row->nip;
							}

							$nama = $row->nama;
							$gol1 = substr($row->gol, 0, strlen($row->gol) - 1);
							$gol2 = substr($row->gol, strlen($row->gol) - 1, 1);
							$jab = $row->jab;
							$upah = $row->upah;
							$visibility = 'hidden';
							$btn = 'Update';
						}
						?>
						<tbody class="nohover">
							<tr>
								<td width="15%">N I P</td>
								<td width="1%">:</td>
								<td>
									<input hidden id="id_anggota" value="<?php echo $id_anggota ?>" />
									<input type="text" id="nip" onkeyup="nip()" maxlength="20" value="<?php echo $nip ?>" />
								</td>
							</tr>
							<tr>
								<td>Nama Lengkap</td>
								<td>:</td>
								<td><input type="text" id="nama" maxlength="160" value="<?php echo $nama ?>" /></td>
							</tr>
							<tr>
								<td>Golongan</td>
								<td>:</td>
								<td>
									<select id="gol1" class="date2">
										<option selected value="0"></option>
										<?php for ($r = 1; $r <= 3; $r++) {
											if ($golongan1[$r] == $gol1) {
												$sel = "selected";
											} else {
												$sel = "";
											} ?>
											<option <?php echo $sel ?> value="<?php echo $golongan1[$r] ?>"><?php echo $golongan1[$r] ?></option>
										<?php } ?>
									</select>
									<select id="gol2" class="date2">
										<option selected value="0"></option>
										<?php for ($r = 1; $r <= 4; $r++) {
											if ($golongan2[$r] == $gol2) {
												$sel = "selected";
											} else {
												$sel = "";
											} ?>
											<option <?php echo $sel ?> value="<?php echo $golongan2[$r] ?>"><?php echo $golongan2[$r] ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Pangkat</td>
								<td>:</td>
								<td><input type="text" id="jab" maxlength="160" value="<?php echo $jab ?>" /></td>
							</tr>
							<tr>
								<td>Upah (gaji) Rp.</td>
								<td>:</td>
								<td><input type="text" id="upah" onkeyup="upah()" maxlength="8" value="<?php echo $upah ?>" /></td>
							</tr>
							<tr style="visibility: <?php echo $visibility ?>">
								<td>Simpanan Awal</td>
								<td>:</td>
								<td>
									<select name="" id="awal">
										<?php
										$this->db->like('nama', 'pokok');
										$sql = $this->db->get('simpanan');
										foreach ($sql->result() as $row) {
										?>
											<option value="<?= $row->id_simpanan ?>"><?= $row->nama ?>(Rp.<?= number_format($row->besar) ?>)</option>

										<?php } ?>

									</select>

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
									<button onclick="window.location.href='<?php echo site_url('anggota') ?>';" class="sub cancel">Batal</button>
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
		<form method="post" action="anggota/hapus">
			<input hidden id="idpop" name="idpop">
			<button type="submit" class="sub">Ya</button>
			<button type="reset" onclick="pophidden()" class="sub cancel">Tidak</button>
		</form>
	</div>

	<script>
		function nip() {
			var n = document.getElementById('nip').value;

			if (n.length > 0 && ((n.charCodeAt(n.length - 1) < 48 || n.charCodeAt(n.length - 1) > 57) && n.charCodeAt(n.length - 1) != 8)) {
				document.getElementById('nip').value = '';
			}
			if (n.length > 1 && +document.getElementById('nip').value == 0) {
				document.getElementById('nip').value = '';
			}
			if (n.length < 2 && +document.getElementById('nip').value == 0) {
				document.getElementById('nip').value = '';
			}
		}

		function upah() {
			var n = document.getElementById('upah').value;

			if (n.length > 0 && ((n.charCodeAt(n.length - 1) < 48 || n.charCodeAt(n.length - 1) > 57) && n.charCodeAt(n.length - 1) != 8)) {
				document.getElementById('upah').value = '';
			}
			if (n.length > 1 && +document.getElementById('upah').value == 0) {
				document.getElementById('upah').value = '';
			}
			if (n.length < 2 && +document.getElementById('upah').value == 0) {
				document.getElementById('upah').value = '';
			}
		}
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
				url: '<?php echo base_url() ?>anggota/cari',
				dataType: 'json',
				data: {
					nama: nama
				},
				success: function(result) {
					window.location.href = '<?php echo base_url() ?>anggota?p=cari';
				}
			});
		});
		$('#simpan').click(function() {
			event.preventDefault();

			var id_anggota = $('#id_anggota').val();
			var nip = $('#nip').val();
			var nama = $('#nama').val();
			var gol1 = $('#gol1').val();
			var gol2 = $('#gol2').val();
			var jab = $('#jab').val();
			var upah = $('#upah').val();
			if (gol1 != '0' && gol2 == '0') {
				$('#gol2').focus();
				return false;
			}
			if (id_anggota == '') {
				var awal = $('#awal').val();
			} else {
				var awal = '1';
			}

			if (nama == '' || jab == '' || upah == '' || awal == '0') {
				if (nama == '') {
					$('#nama').focus();
				} else if (jab == '') {
					$('#jab').focus();
				} else if (upah == '') {
					$('#upah').focus();
				} else if (awal == '0') {
					$('#awal').focus();
				}
				return false;
			}

			$.ajax({
				type: 'post',
				url: (id_anggota == '') ? '<?php echo base_url() ?>anggota/tambah' : '<?php echo base_url() ?>anggota/ubah',
				dataType: 'json',
				data: {
					nip: nip,
					nama: nama,
					gol1: gol1,
					gol2: gol2,
					jab: jab,
					upah: upah,
					awal: awal
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