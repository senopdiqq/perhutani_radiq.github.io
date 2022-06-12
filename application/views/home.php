<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>

<head>
	<title>Perum Perhutani - Koperasi Simpan Pinjam</title>
	<meta charset="utf-8">
	<meta name="theme-color" content="#333333">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="icon" href="asset/image/icon.png" type="image/png" sizes="32x32">
	<link rel="stylesheet" href="asset/css/style.css">
	<script src="asset/js/jquery-3.4.1.min.js"></script>
</head>

<body>
	<div class="wall"></div>
	<h1 class="h1login">KOPERASI SIMPAN PINJAM<br><span>Perum Perhutani KPH Pasuruan</span></h1>
	<h2 class="h2login"></h2>
	<div class="formlogin">
		<input type="text" id="user" placeholder="Username" maxlength="160" />
		<input type="password" id="pass" placeholder="Password" maxlength="160" />
		<label class="notif" id="notif"></label>
		<button id="login"> Log In</button>
	</div>
	<label class="footlogin">Radiq Arbi L- Brawijaya Univ. 2021</label>
	<script>
		$('#login').click(function() {
			event.preventDefault();

			var user = $('#user').val();
			var pass = $('#pass').val();

			if (user == '' || pass == '') {
				if (user == '') {
					$('#user').focus();
				} else if (pass == '') {
					$('#pass').focus();
				}
				return false;
			}

			$.ajax({
				url: '<?php echo base_url() ?>login/in',
				type: 'post',
				dataType: 'json',
				data: {
					user: user,
					pass: pass
				},
				success: function(result) {
					notified(result);
				}
			});
		});

		function notified(err) {
			var e = parseInt(err.split('#')[0]);
			var msg = document.getElementById('notif');

			if (e == 0) {
				msg.innerText = err.split('#')[1];
				setTimeout(function() {
					document.getElementById('user').focus();
					msg.innerText = '';
				}, 2000);
			} else {
				window.location.href = '<?php echo base_url() ?>' + err.split('#')[1];
			}
		}
	</script>
</body>

</html>