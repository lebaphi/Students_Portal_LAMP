<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>T360 | Reset Password</title>
	<meta name="description" content="Reset password">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
			},
			active: function () {
				sessionStorage.fonts = true;
			}
		});
	</script>
	<link href="../assets/vendors/global/vendors.bundle.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style.bundle.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="../assets/media/logos/favicon.ico"/>
</head>

<div class="col-lg-12 kt-padding-30">
	<div><h3>Reset Password</h3></div>
	<div style="border-style: solid; border-width: 0.5px; border-color: cadetblue;"></div>
	<body>
	<?php
		if ($isValid) {
			echo '
				<form>
					<input type="hidden" name="email" value="' . $md5Email . '">
					<input type="hidden" name="resetPassSubmit">
					<input class="form-control kt-input col-lg-3 kt-margin-t-5" type="password" placeholder="Enter new password" id="newPassword" name="newPassword">
					<input class="form-control kt-input col-lg-3 kt-margin-t-5" type="password" placeholder="Confirm password" name="confirmPassword">
					<input class="btn btn-brand kt-margin-t-5" type="button" id="resetPasswordBtn" value="Submit">
				</form>';
		} else {
			echo '
				<div>The request successful</div>
				<a href="../" class="btn btn-brand kt-margin-t-5">Login</a>';
		}
	?>
	</body>
</div>

<script src="../assets/vendors/global/vendors.bundle.js" type="text/javascript"></script>
<script src="../assets/js/pages/components/extended/toastr.js" type="text/javascript"></script>
<script>
	toastr.options = {
		"closeButton": false,
		"debug": false,
		"newestOnTop": false,
		"progressBar": false,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};
</script>

<script>
	$('#resetPasswordBtn').click(function (e) {
		e.preventDefault();
		const btn = $(this);
		const form = $(this).closest('form');
		form.validate({
			rules: {
				newPassword: "required",
				confirmPassword: {
					equalTo: "#newPassword"
				}
			},
			messages: {
				newPassword: "Enter password",
				confirmPassword: "Confirm password does not match"
			}
		});
		if (!form.valid()) {
			return;
		}
		btn.attr('disabled', true);
		form.ajaxSubmit({
			url: './auth.php',
			type: 'POST',
			success: function (response) {
				try {
					response = JSON.parse(response);
					if (response.result === 'ok') {
						toastr.success(response.msg);
						setTimeout(() => {
							location.reload();
						}, 1500)
					} else {
						toastr.error(response.msg);
					}
				} catch (err) {
				}
			}
		});
	});
</script>