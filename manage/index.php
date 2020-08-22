<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Dashboard</title>
	<meta name="description" content="Latest updates and statistic charts">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href=""/>
	
	<?php
		session_start();
		require_once '../services/UserService.php';
		require_once '../services/EnvService.php';
		
		$appVersion = EnvService::getInstance()->getEnvByKey('APP_VERSION');
		if (!$appVersion) {
			$appVersion = '1.0.0';
		}
		$currentUser = UserService::getInstance()->getCurrentUser();
		if (!$currentUser->isAuthenticated()) {
			header('Location: ../');
			exit();
		}
		
		require_once '../form-ui/components/page.css.php';
		require_once '../form-ui/components/page.script.php';
	?>
</head>

<script>
	$(document).ready(function () {
		const navBarElm = getElmByIdJQuery('kt_header_menu_wrapper');
		navBarElm.addClass('disable-nav-bar');
		
		const loadPageOption = {action: 'renderPage'};
		
		$('ul#mainNav li a').click(function (elms) {
			const page = $(this).attr('name');
			if (page) {
				loadPage(page, loadPageOption, callback);
			}
		});
		
		$('div#mainProfile div a').click(function (elms) {
			const page = $(this).attr('name');
			if (page) {
				loadPage(page, loadPageOption, callback);
			}
		});
		
		function callback(response) {
			if (!response) {
				loadPage('./errors/404.php', loadPageOption, () => {
					toastr.error('Page not found');
				});
			}
		}
		
		/**
		 * get current user
		 */
		async function loadCurrentUser() {
			return new Promise(resolve => {
				sendRequest('formGetCurrentUser', {
					url: 'new-user/user.php',
					method: 'GET'
				}, (response) => {
					if (isSuccess(response)) {
						const currentUser = setReadOnlyObject(response.data);
						setRouteVariable(COMMON, {currentUser});
						resolve('ok');
					} else {
						toastr.error(response.msg);
					}
				})
			});
		}
		
		function loadDefault(page) {
			loadCurrentUser().then(() => {
				loadPage(page ? page : './new-dashboard/dashboard.php', loadPageOption, (response) => {
					if (!response) {
						loadDefault(page);
					} else {
						navBarElm.removeClass('disable-nav-bar');
						localStorage.removeItem('defaultPage');
					}
				});
			});
		}
		
		loadDefault(localStorage.getItem('defaultPage'));
	});
</script>

<body style="background-image: url(../assets/media/demos/demo4/header.jpg); background-position: center top; background-size: 100% 350px;"
      class="kt-page--loading-enabled kt-page--loading kt-page--fixed kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-menu kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading">

<div id="kt_header_mobile" class="kt-header-mobile kt-header-mobile--fixed ">
	<div class="kt-header-mobile__logo">
		<a href="index.php">
			<img alt="Logo" src=""/>
		</a>
	</div>
	<div class="kt-header-mobile__toolbar">
		<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
		<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more-1"></i></button>
	</div>
</div>

<div class="kt-grid kt-grid--hor kt-grid--root">
	<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
			<div id="kt_header" class="kt-header kt-header--fixed " data-ktheader-minimize="on">
				<div class="kt-container">
					<?php
						require_once '../form-ui/components/nav.bar.php';
					?>
				</div>
			</div>
			
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-grid--stretch">
				<div class="kt-container kt-body  kt-grid kt-grid--ver" id="kt_body">
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
						<div id="mainContents"></div>
					</div>
				</div>
			</div>
			
			<?php
				require_once '../form-ui/components/footer.bar.php';
			?>
		</div>
	</div>
</div>

<div id="kt_scrolltop" class="kt-scrolltop">
	<i class="fa fa-arrow-up"></i>
</div>

<form id="formGetCurrentUser" class="none-display">
	<input name="action" value="getCurrentUser">
</form>

</body>
</html>
