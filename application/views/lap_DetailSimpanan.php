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
    <script src="asset/js/jquery-3.4.1.min.js"></script>
    <script src="asset/js/function.js"></script>
</head>

<body>
    <div class="header">
        <h3 class="home">KOPERASI SIMPAN PINJAM<br><span>Perum Perhutani KPH Pasuruan</span></h3>
        <label><?php echo ucwords($this->session->userdata('nama')); ?></label>
        <img href="<?= base_url('lap_simpanan') ?>" src="../asset/image/admin.png">
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
            <li class="lisub" onclick="window.location.href='<?php echo site_url('lap_pinjaman') ?>';"><i class="fas fa-table"></i>Laporan Pinjaman</li>
            <li class="lisub active" onclick="window.location.href='<?php echo site_url('lap_simpanan') ?>';"><i class="fas fa-table"></i>Laporan Simpanan</li>
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
            <h1>Laporan Simpanan<b>'<?= $detail_nama ?>'</b> </h1>

        </div>
        <div class="col-6 right">
            <h1> <a onclick="printContent('print')" style="padding-bottom: 1%" href="<?php echo site_url('lap_simpanan/detail') ?>?id=<?php echo $id_simpan ?>">Print</a> </h1>
        </div>

        <div class="col-12">
            <div class="box shadow" id="print">
                <table table-striped table-bordered>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
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


                            @$tot += $det->jumlah;
                            $id_h = $det->id_simpanan;

                        ?>
                            <tr style="text-align: center;">
                                <td><?= $tbt ?></td>
                                <td><?= $det->nama ?></td>
                                <td>Menabung</td>
                                <td>Rp. <?= number_format($det->jumlah) ?></td>
                            </tr>

                        <?php $i++;
                        } ?>



                    </tbody>
                    <tbody>
                        <?php
                        $s = 1;
                        foreach ($tarik_tunai as $key) {
                            $bulan2 = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des');
                            $tgl2 = date('d', strtotime($key->tanggal));
                            $bln2 = $bulan2[(int) date('m', strtotime($key->tanggal))];
                            $thn2 = date('Y', strtotime($key->tanggal));
                            $tbt2 = $tgl2 . ' ' . $bln2 . ' ' . $thn2;

                            @$JPU += $key->jumlah;
                        ?>
                            <tr style="text-align: center;">
                                <td><?= $tbt2 ?></td>
                                <td><b>-</b></td>
                                <td>Pengambilan Uang</td>
                                <td>Rp.<?= number_format($key->jumlah) ?></td>
                            </tr>
                        <?php $s++;
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr style="text-align: center;">
                            <th>Total Tabungan:</th>
                            <th></th>
                            <th></th>
                            <th>Rp.<?= number_format(@$tot) ?></th>
                        </tr>
                        <tr style="background: grey;height: 20px">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="text-align: center;">
                            <th>Total Pengambilan Uang:</th>
                            <th></th>
                            <th></th>
                            <th>Rp.<?= number_format(@$JPU) ?></th>
                        </tr>
                        <tr style="background: grey;height: 20px">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr style="text-align: center;">
                            <th>Saldo Akhir:</th>
                            <th></th>
                            <th></th>
                            <th>Rp.<?= number_format(@$tot - @$JPU) ?></th>
                        </tr>
                    </tfoot>
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