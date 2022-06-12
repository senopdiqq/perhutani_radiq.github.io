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
		<?php if ($this->session->userdata('user') == 'admin') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('dashboard') ?>';"><i class="fas fa-donate"></i>Dashboard</li>
			<li class="lisub active"><i class="fas fa-user-shield"></i>U&nbsp;&nbsp;s&nbsp;&nbsp;e&nbsp;&nbsp;r</li>
		<?php }
		if ($this->session->userdata('user') == 'admin' || $this->session->userdata('user') == 'bendahara') { ?>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('simpanan') ?>';"><i class="fas fa-donate"></i>Simpanan</li>
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
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pengajuan') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
		<?php } ?>
	</ul>
	<div class="content">
		<div class="col-6 left">
			<h1>Admin</h1>
			<?php if (!isset($_GET['p'])) { ?>
				<button class="sub" onclick="window.location.href='<?php echo site_url('admin') ?>?p=tambah';">Tambah</button>
			<?php } ?>
		</div>
		<div class="col-6 right">
		</div>
		<div class="col-12">
			<div class="box shadow">
				<table>
					<?php
					$status = array('', 'Bendahara', 'Karyawan');
					?>
					<?php if (!isset($_GET['p'])) { ?>
						<thead>
							<tr>
								<th width="35%">Username</th>
								<th width="20%">Password</th>
								<th width="30%">Status</th>
								<th width="15%">Aksii</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$this->db->order_by('id_admin asc');
							$sql = $this->db->get('admin');
							foreach ($sql->result() as $row) {
							?>
								<tr>
									<td align="center"><?php echo $row->user ?></td>
									<td align="center">*****</td>
									<td align="center"><?php echo $row->stts ?></td>
									<td align="center">
										<i onclick="window.location.href='<?php echo site_url('admin/ubah') ?>?id=<?php echo $row->id_admin ?>';" class="fas fa-pen" title="Ubah"></i>
										<i onclick="pophapus(<?php echo $row->id_admin ?>)" class="fas fa-eraser" title="Hapus"></i>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					<?php } else { ?>
						<?php
						$id_admin = '';
						$user = '';
						$pass = '';
						$stts = '';
						$btn = 'Simpan';

						if ($_GET['p'] == 'ubah') {
							$sql = $this->db->get_where('admin', array('id_admin' => $this->session->userdata('id_admin')));
							$row = $sql->row();
							$id_admin = $row->id_admin;
							$user = $row->user;
							$stts = $row->stts;
							$btn = 'Update';
						}
						?>
						<tbody class="nohover">
							<tr>
								<td>Username</td>
								<td>:</td>
								<td>
									<input hidden id="id_admin" value="<?php echo $id_admin ?>" />
									<input type="text" id="user" maxlength="160" value="<?php echo $user ?>" />
								</td>
							</tr>
							<tr>
								<td width="15%">Password</td>
								<td width="1%">:</td>
								<td><input type="password" id="pass" maxlength="160" value="<?php echo $pass ?>" /></td>
							</tr>
							<tr>
								<td>Status</td>
								<td>:</td>
								<td>
									<select id="stts">
										<option selected value="0"></option>
										<?php for ($r = 1; $r <= 2; $r++) {
											if ($status[$r] == $stts) {
												$sel = "selected";
											} else {
												$sel = "";
											} ?>
											<option <?php echo $sel ?> value="<?php echo $status[$r] ?>"><?php echo $status[$r] ?></option>
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
									<button onclick="window.location.href='<?php echo site_url('admin') ?>';" class="sub cancel">Batal</button>
								</td>
							</tr>
						</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
	<label class="footpage">Radiq Arbi L- Brawijaya Univ. 2021</label>
	<div id="popdel" class="popdel"></div>
	<div id="boxdel" class="boxdel">
		Hapus Data ?<br /><br /><br />
		<form method="post" action="admin/hapus">
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
		$('#simpan').click(function() {
			event.preventDefault();

			var id_admin = $('#id_admin').val();
			var user = $('#user').val();
			var pass = $('#pass').val();
			var stts = $('#stts').val();

			if (user == '' || pass == '' || stts == '0') {
				if (user == '') {
					$('#user').focus();
				} else if (pass == '') {
					$('#pass').focus();
				} else if (stts == '0') {
					$('#stts').focus();
				}
				return false;
			}

			$.ajax({
				type: 'post',
				url: (id_admin == '') ? '<?php echo base_url() ?>admin/tambah' : '<?php echo base_url() ?>admin/ubah',
				dataType: 'json',
				data: {
					user: user,
					pass: pass,
					stts: stts
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