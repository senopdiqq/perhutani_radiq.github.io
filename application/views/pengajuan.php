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

		<?php if ($this->session->userdata('user') == 'karyawan') { ?>
			<li class="none"><i class="fas fa-cash-register"></i>Transaksi</>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('tabungan') ?>';"><i class="fas fa-donate"></i>Simpanan</li>
			<li class="lisub active" onclick="window.location.href='<?php echo site_url('pengajuan') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('tarik_tunai') ?>';"><i class="fas fa-money-bill"></i>Ambil Tabungan</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_pinjaman') ?>';"><i class="fas fa-table"></i>Laporan Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_simpanan') ?>';"><i class="fas fa-table"></i>Laporan Simpanan</li>
		<?php }
		if ($this->session->userdata('user') == 'bendahara') { ?>
			<li class="none"><i class="fas fa-database"></i>M a s t e r</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('dashboard') ?>';"><i class="fas fa-donate"></i>Dashboard</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('simpanan') ?>';"><i class="fas fa-donate"></i>Simpanan</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('pinjaman') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('anggota') ?>';"><i class="fas fa-users"></i>Anggota</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_pinjaman') ?>';"><i class="fas fa-table"></i>Laporan Pinjaman</li>
			<li class="lisub" onclick="window.location.href='<?php echo site_url('lap_simpanan') ?>';"><i class="fas fa-table"></i>Laporan Simpanan</li>
			<li class="none"><i class="fas fa-cash-register"></i>Transaksi</li>
			<li class="lisub active" onclick="window.location.href='<?php echo site_url('pengajuan') ?>';"><i class="fas fa-credit-card"></i>Pinjaman</li>
		<?php } ?>
	</ul>
	<div class="content">
		<div class="col-6 left">
			<h1>Pinjaman</h1>
			<?php if ($this->session->userdata('user') == 'karyawan' && (!isset($_GET['p']) || $_GET['p'] == 'cari')) { ?>
				<button class="sub" onclick="window.location.href='<?php echo site_url('pengajuan') ?>?p=tambah';">Tambah</button>
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
								<th width="30%">Nama Lengkap</th>
								<th width="15%">Jumlah Rp.</th>
								<th width="10%">Waktu</th>
								<th width="20%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$this->db->order_by('pengajuan.id_pengajuan desc');
							$this->db->select('pengajuan.id_pengajuan as id_pengajuan,pengajuan.tanggal as tanggal,anggota.nama as anggota,pengajuan.jumlah as jumlah,pengajuan.tenor as tenor');
							$this->db->from('pengajuan');
							$this->db->join('anggota', 'anggota.id_anggota=pengajuan.id_anggota', 'left');
							if (!isset($_GET['p'])) {
							} else {
								$this->db->like('anggota.nama', $this->session->userdata('cari_pengajuan'), 'both');
							}
							$this->db->where('pengajuan.sisa>', '0');
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
									<td align="center"><?php echo number_format($row->jumlah, 0, '.', ',') ?></td>
									<td align="center"><?php echo $row->tenor ?> bln</td>
									<td align="center">
										<i onclick="window.location.href='<?php echo site_url('pengajuan/detail') ?>?id=<?php echo $row->id_pengajuan ?>';" class="fas fa-eye" title="Check"></i>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					<?php } else { ?>
						<?php if ($_GET['p'] == 'tambah') { ?>
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
												<option value="<?php echo $row->id_anggota ?>|<?php echo $row->upah ?>"><?php echo $row->nama; ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td width="15%">Jenis</td>
									<td width="1%">:</td>
									<td>
										<select id="pinjaman">
											<option selected value="0"></option>
											<?php
											$this->db->order_by('id_pinjaman asc');
											$sql = $this->db->get('pinjaman');
											foreach ($sql->result() as $row) {
											?>
												<option value="<?php echo $row->id_pinjaman ?>|<?php echo $row->maks ?>|<?php echo $row->mins ?>"><?php echo $row->nama . ' - ' . number_format($row->maks, 0, '.', ','); ?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td>Besar Rp.</td>
									<td>:</td>
									<td><input disabled type="text" id="besar" onkeyup="besar()" maxlength="8" value="" /></td>
								</tr>
								<tr>
									<td>Waktu</td>
									<td>:</td>
									<td><input disabled style="width: 7em !important;" type="text" id="waktu" onkeyup="waktu()" maxlength="2" value="" /> bln</td>
								</tr>
								<tr>
									<td>Denda Rp.</td>
									<td>:</td>
									<td><input style="width: 7em !important;" disabled type="text" id="denda" onkeyup="denda()" maxlength="5" value="" /> / hari</td>
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
										<button onclick="window.location.href='<?php echo site_url('pengajuan') ?>';" class="sub cancel">Batal</button>
									</td>
								</tr>
							</tbody>
						<?php } else if ($_GET['p'] == 'detail') { ?>
							<?php
							$this->db->order_by('pengajuan.id_pengajuan desc');
							$this->db->select('pengajuan.id_pengajuan as id_pengajuan,pengajuan.tanggal as tanggal,anggota.nama as anggota,pinjaman.nama as pinjaman,pengajuan.jumlah as jumlah,pengajuan.tenor as tenor,pengajuan.sisa as sisa,pengajuan.denda as denda,pengajuan.status as status');
							$this->db->from('pengajuan');
							$this->db->join('pinjaman', 'pinjaman.id_pinjaman=pengajuan.id_pinjaman', 'left');
							$this->db->join('anggota', 'anggota.id_anggota=pengajuan.id_anggota', 'left');
							$this->db->where('pengajuan.id_pengajuan', $this->session->userdata('id_pengajuan'));
							$sql = $this->db->get();
							$row = $sql->row();

							$tgl = date('d', strtotime($row->tanggal));
							$bln = $bulan[(int) date('m', strtotime($row->tanggal))];
							$thn = date('Y', strtotime($row->tanggal));
							$tbt = $tgl . ' ' . $bln . ' ' . $thn;
							$anggota = $row->anggota;
							$pinjaman = $row->pinjaman;
							$besar = number_format($row->jumlah, 0, '.', ',');
							$angsur = number_format(($row->jumlah / $row->tenor) + (($row->jumlah / 100) * 1.3), 0, '.', ',');
							$waktu = $row->tenor;
							$sisa = $row->sisa;
							$denda = number_format($row->denda, 0, '.', ',');
							if ($row->status == 'Ya') {
								$status = 'Disetujui';
							} else if ($row->status == 'Tidak') {
								$status = 'Belum Disetujui';
							}
							$jumlah = number_format((($row->jumlah / $row->tenor) + (($row->jumlah / 100) * 1.3)) * $sisa, 0, '.', ',');
							?>

							<button id="cicil">Angsur</button>
							<button id="lunas">Bayar Lunas</button>

							<tbody id="mencicil" class="nohover">
								<tr>
									<td>Tanggal</td>
									<td>:</td>
									<td>
										<input readonly="readonly" type="text" value="<?php echo $tbt ?>" />
									</td>
								</tr>
								<tr>
									<td>Anggota</td>
									<td>:</td>
									<td><input readonly="readonly" type="text" value="<?php echo $anggota ?>" /></td>
								</tr>
								<tr>
									<td>Jenis</td>
									<td>:</td>
									<td><input readonly="readonly" type="text" value="<?php echo $pinjaman ?>" /></td>
								</tr>
								<tr>
									<td>Besar Rp.</td>
									<td>:</td>
									<td><input readonly="readonly" type="text" value="<?php echo $besar ?>" /></td>
								</tr>
								<tr>
									<td>Waktu</td>
									<td>:</td>
									<td><input style="width: 7em !important;" type="text" value="<?php echo $waktu ?>" /> bln</td>
								</tr>
								<?php if ($row->status == 'Ya') { ?>
									<tr>
										<td>Angsuran Rp.</td>
										<td>:</td>
										<td><input id="jmlangsur" readonly="readonly" type="text" value="<?php echo $angsur ?>" /></td>
									</tr>
									<tr>
										<td>Sisa</td>
										<td>:</td>
										<td><input style="width: 7em !important;" type="text" value="<?php echo $sisa ?>" /> bln</td>
									</tr>
								<?php } ?>
								<tr>
									<td>Denda Rp.</td>
									<td>:</td>
									<td><input style="width: 7em !important;" readonly="readonly" type="text" value="<?php echo $denda ?>" /> / hari</td>
								</tr>
								<?php if ($row->status == 'Ya') { ?>
									<tr>
										<td>Jml Tagihan Rp.</td>
										<td>:</td>
										<td><input readonly="readonly" type="text" value="<?php echo $jumlah ?>" /></td>
									</tr>
								<?php } ?>
								<?php if ($this->session->userdata('user') == 'karyawan' && $waktu == $sisa) { ?>
									<tr>
										<td>Status</td>
										<td>:</td>
										<td><input readonly="readonly" type="text" value="<?php echo $status ?>" /></td>
									</tr>
								<?php } ?>
								<tr>
									<td></td>
									<td></td>
									<td><label class="notifpage" id="notif"></label></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td>
										<?php if ($this->session->userdata('user') == 'karyawan') { ?>
											<?php if ($row->status == 'Ya') { ?>
												<button id="angsur" class="sub">Angsur</button>
											<?php } ?>
											<button onclick="window.location.href='<?php echo site_url('pengajuan') ?>';" class="sub cancel">Kembali</button>
										<?php } ?>
										<?php if ($this->session->userdata('user') == 'bendahara') { ?>
											<?php if ($row->status == 'Tidak') { ?>
												<button id="terima" class="sub">Terima</button>
											<?php } ?>
											<button onclick="window.location.href='<?php echo site_url('pengajuan') ?>';" class="sub cancel">Kembali</button>
										<?php } ?>
									</td>
								</tr>
							</tbody>

							<tbody id="melunasi" class="nohover">

								<tr>
									<td>Tanggal</td>
									<td>:</td>
									<td>
										<input readonly="readonly" type="text" value="<?php echo $tbt ?>" />
									</td>
								</tr>
								<tr>
									<td>Anggota</td>
									<td>:</td>
									<td><input readonly="readonly" type="text" value="<?php echo $anggota ?>" /></td>
								</tr>
								<tr>
									<td>Jenis</td>
									<td>:</td>
									<td><input readonly="readonly" type="text" value="<?php echo $pinjaman ?>" /></td>
								</tr>
								<tr>
									<td>Besar Rp.</td>
									<td>:</td>
									<td><input readonly="readonly" type="text" value="<?php echo $besar ?>" /></td>
								</tr>
								<tr>
									<td>Denda Rp.</td>
									<td>:</td>
									<td><input style="width: 7em !important;" readonly="readonly" type="text" value="<?php echo $denda ?>" /> / hari</td>
								</tr>
								<?php if ($row->status == 'Ya') { ?>
									<tr>
										<td>Jml Tagihan Rp.</td>
										<td>:</td>
										<td><input id="bLunas" readonly="readonly" type="text" value="<?php echo $jumlah ?>" /></td>
									</tr>
								<?php } ?>
								<?php if ($this->session->userdata('user') == 'karyawan' && $waktu == $sisa) { ?>
									<tr>
										<td>Status</td>
										<td>:</td>
										<td><input readonly="readonly" type="text" value="<?php echo $status ?>" /></td>
									</tr>
								<?php } ?>
								<tr>
									<td></td>
									<td></td>
									<td><label class="notifpage" id="notif"></label></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td>
										<?php if ($this->session->userdata('user') == 'karyawan') { ?>
											<?php if ($row->status == 'Ya') { ?>
												<button id="tLunas" class="sub">Bayar</button>
											<?php } ?>
											<button onclick="window.location.href='<?php echo site_url('pengajuan') ?>';" class="sub cancel">Kembali</button>
										<?php } ?>
										<?php if ($this->session->userdata('user') == 'bendahara') { ?>
											<?php if ($row->status == 'Tidak') { ?>
												<button id="terima" class="sub">Terima</button>
											<?php } ?>
											<button onclick="window.location.href='<?php echo site_url('pengajuan') ?>';" class="sub cancel">Kembali</button>
										<?php } ?>
									</td>
								</tr>


							</tbody>
						<?php } ?>
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

	<div id="popdet" class="popdel"></div>
	<div id="boxdet" class="boxdel">
		<button id="proses" class="sub">Ya</button>
	</div>

	<script>
		$('#mencicil').fadeOut();
		$('#melunasi').fadeOut();

		$('#cicil').click(function() {
			$('#mencicil').fadeIn();
			$('#melunasi').fadeOut();
			// $('#cicil').fadeOut();		
			// $('#lunas').fadeIn();	
		});

		$('#lunas').click(function() {
			$('#mencicil').fadeOut();
			$('#melunasi').fadeIn();
			// $('#lunas').fadeOut();
			// $('#cicil').fadeIn();
		});

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

			var anggota = document.getElementById('anggota').value;
			var pinjaman = document.getElementById('pinjaman').value;

			if (anggota != '0' && pinjaman != '0') {
				var upah = +document.getElementById('anggota').value.split('|')[1];
				var mins = +document.getElementById('pinjaman').value.split('|')[2];
				if (upah < mins) {
					var msg = document.getElementById('notif');
					msg.innerText = 'Jenis Tidak Disetujui';
					setTimeout(function() {
						msg.innerText = '';
					}, 2000);

					document.getElementById('besar').value = '';
				}
			}

			if (pinjaman != '0') {
				var jml = +document.getElementById('pinjaman').value.split('|')[1];
				if (n > jml) {
					var msg = document.getElementById('notif');
					msg.innerText = 'Besar Rp. Melebihi Plafond';
					setTimeout(function() {
						msg.innerText = '';
					}, 2000);

					document.getElementById('besar').value = '';
				}
			}
		}

		function waktu() {
			var n = document.getElementById('waktu').value;

			if (n.length > 0 && ((n.charCodeAt(n.length - 1) < 48 || n.charCodeAt(n.length - 1) > 57) && n.charCodeAt(n.length - 1) != 8)) {
				document.getElementById('waktu').value = '';
			}
			if (n.length > 1 && +document.getElementById('waktu').value == 0) {
				document.getElementById('waktu').value = '';
			}
			if (n.length < 2 && +document.getElementById('waktu').value == 0) {
				document.getElementById('waktu').value = '';
			}
		}

		function denda() {
			var n = document.getElementById('denda').value;

			if (n.length > 0 && ((n.charCodeAt(n.length - 1) < 48 || n.charCodeAt(n.length - 1) > 57) && n.charCodeAt(n.length - 1) != 8)) {
				document.getElementById('denda').value = '';
			}
			if (n.length > 1 && +document.getElementById('denda').value == 0) {
				document.getElementById('denda').value = '';
			}
			if (n.length < 2 && +document.getElementById('denda').value == 0) {
				document.getElementById('denda').value = '';
			}
		}
		$('#anggota').change(function() {
			document.getElementById('besar').value = '';
			document.getElementById('waktu').value = '';
			document.getElementById('denda').value = '';
		});
		$('#pinjaman').change(function() {
			var jns = document.getElementById('pinjaman').value;
			if (jns != '0') {
				document.getElementById('besar').disabled = false;
				document.getElementById('waktu').disabled = false;
				document.getElementById('denda').disabled = false;
			} else {
				document.getElementById('besar').disabled = true;
				document.getElementById('waktu').disabled = true;
				document.getElementById('denda').disabled = true;
			}

			document.getElementById('besar').value = '';
			document.getElementById('waktu').value = '';
			document.getElementById('denda').value = '';
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
				url: '<?php echo base_url() ?>pengajuan/cari',
				dataType: 'json',
				data: {
					nama: nama
				},
				success: function(result) {
					window.location.href = '<?php echo base_url() ?>pengajuan?p=cari';
				}
			});
		});
		$('#simpan').click(function() {
			event.preventDefault();

			var tgl = $('#tgl').val();
			var bln = $('#bln').val();
			var thn = $('#thn').val();
			var anggota = $('#anggota').val();
			var pinjaman = $('#pinjaman').val();
			var besar = $('#besar').val();
			var waktu = $('#waktu').val();

			if (tgl == '0' || bln == '0' || thn == '0' || anggota == '0' || pinjaman == '0' || besar == '' || waktu == '') {
				if (tgl == '0') {
					$('#tgl').focus();
				} else if (bln == '0') {
					$('#bln').focus();
				} else if (thn == '0') {
					$('#thn').focus();
				} else if (anggota == '0') {
					$('#anggota').focus();
				} else if (pinjaman == '0') {
					$('#pinjaman').focus();
				} else if (besar == '') {
					$('#besar').focus();
				} else if (waktu == '') {
					$('#waktu').focus();
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
			var pinjaman = $('#pinjaman').val();
			var besar = $('#besar').val();
			var waktu = $('#waktu').val();
			var denda = $('#denda').val();

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>pengajuan/proses',
				dataType: 'json',
				data: {
					tgl: tgl,
					bln: bln,
					thn: thn,
					anggota: anggota,
					pinjaman: pinjaman,
					besar: besar,
					waktu: waktu,
					denda: denda
				},
				success: function(result) {
					notified(result);
				}
			});
		});
		$('#terima').click(function() {
			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>pengajuan/terima',
				dataType: 'json',
				success: function(result) {
					notified(result);
				}
			});
		});
		$('#angsur').click(function() {
			event.preventDefault();

			var jmlangsur = $('#jmlangsur').val();

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>pengajuan/angsur',
				dataType: 'json',
				data: {
					jmlangsur: jmlangsur
				},
				success: function(result) {
					notified(result);
				}
			});
		});

		$('#tLunas').click(function() {
			event.preventDefault();

			var lunas = $('#bLunas').val();

			$.ajax({
				type: 'post',
				url: '<?php echo base_url() ?>pengajuan/bayarLunas',
				dataType: 'json',
				data: {
					lunas: lunas
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