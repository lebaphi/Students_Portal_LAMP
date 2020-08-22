<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->
<head>
	
	<!--begin::Base Path (base relative path for assets of this page) -->
	<base href="./authenticate/">
	
	<!--end::Base Path -->
	<meta charset="utf-8" />
	<title>Title | Login</title>
	<meta name="description" content="Login">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!--begin::Fonts -->
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	
	<!--end::Fonts -->
	
	<!--begin::Page Custom Styles(used by this page) -->
	<link href="../assets/css/pages/general/login/login-4.css" rel="stylesheet" type="text/css" />
	
	<!--end::Page Custom Styles -->
	
	<!--begin::Global Theme Styles(used by all pages) -->
	<link href="../assets/vendors/global/vendors.bundle.css" rel="stylesheet" type="text/css" />
	<link href="../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	
	<!--end::Layout Skins -->
	<link rel="shortcut icon" href="../assets/media/logos/default-sm.png" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
	<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
			<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper" style="background-image: url(../assets/media/bg/bg-2.jpg);border-radius: 40px; margin-top: 20px">
				<div class="kt-login__container">
					<div class="kt-login__head">
							<h2 class="kt-login__title">Title | Login</h2>
						</div>
						<div class="kt-login__logo">
						<a href="javascript:;">
							<img src="../assets/media/logos/default.png" style="height: 80px;">
						</a>
					</div>
					
					<div class="kt-login__signin">
						<div class="kt-login__container kt-align-center">
							<h3 class=""></h3>
						</div>
						<form class="kt-form">
							<div class="input-group">
								<input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
							</div>
							<div class="input-group">
								<input class="form-control" type="password" placeholder="Password" name="password">
							</div>
							<div class="row kt-login__extra">
								<div class="col">
									<label class="kt-checkbox">
										<input type="checkbox" name="remember"> Keep Session Active
										<span></span>
									</label>
								</div>
								<div class="col kt-align-right">
									<a href="javascript:;" id="kt_login_forgot" class="kt-login__link">Forget Password ?</a>
								</div>
							</div>
							<div class="kt-login__actions">
								<input type="hidden" name="loginSubmit">
								<button id="kt_login_signin_submit" name="loginSubmit" class="btn btn-brand btn-pill kt-login__btn-primary">Sign In</button>
							</div>
						</form>
					</div>
					
					<div class="kt-login__forgot">
						<div class="kt-login__head">
							<h3 class="kt-login__title">Forgotten Password ?</h3>
							<div class="kt-login__desc">Enter your email to reset your password:</div>
						</div>
						<form class="kt-form">
							<div class="input-group">
								<input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
							</div>
							<div class="kt-login__actions">
								<input type="hidden" name="forgotSubmit">
								<button id="kt_login_forgot_submit" class="btn btn-brand btn-pill kt-login__btn-primary">Request</button>&nbsp;&nbsp;
								<button id="kt_login_forgot_cancel" class="btn btn-secondary btn-pill kt-login__btn-secondary">Cancel</button>
							</div>
						</form>
					</div>
				</div>
				<div class="kt-footer__menu kt-align-center"></div>
			</div>
			<div class="kt-footer__menu kt-align-center">© 2020 Co Name - All Rights Reserved.
</div>
		</div>
	</div>
</div>

<!-- end:: Page -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
	var KTAppOptions = {
		"colors": {
			"state": {
				"brand": "#5d78ff",
				"dark": "#282a3c",
				"light": "#ffffff",
				"primary": "#5867dd",
				"success": "#34bfa3",
				"info": "#36a3f7",
				"warning": "#ffb822",
				"danger": "#fd3995"
			},
			"base": {
				"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
				"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
			}
		}
	};
</script>

<!-- end::Global Config -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="../assets/vendors/global/vendors.bundle.js" type="text/javascript"></script>
<script src="../assets/js/scripts.bundle.js" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts(used by this page) -->
<script src="auth.script.js" type="text/javascript"></script>

<!--end::Page Scripts -->
</body>

<!-- end::Body -->
</html>
