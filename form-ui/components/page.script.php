<script src="../assets/vendors/custom/webfont/webfont.js"></script>
<script src="../assets/vendors/global/vendors.bundle.js" type="text/javascript"></script>
<script src="../assets/js/scripts.bundle.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/jquery-ui/jquery-ui.bundle.js" type="text/javascript"></script>

<script src="../assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/datatables/dataTables.rowReorder.min.js" type="text/javascript"></script>
<script src="../assets/js/pages/crud/datatables/extensions/buttons.js" type="text/javascript"></script>
<script src="../assets/js/pages/components/extended/toastr.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/moment/moment.js" type="text/javascript"></script>
<script src="../assets/js/pages/custom/apps/user/profile.js" type="text/javascript"></script>

<script>
	
	WebFont.load({
		google: {
			"families": ["Poppins:300,400,500,600,700"]
		},
		active: function () {
			sessionStorage.fonts = true;
		}
	});
	
	const KTAppOptions = {
		"colors": {
			"state": {
				"brand": "#366cf3",
				"light": "#ffffff",
				"dark": "#282a3c",
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
	
	const tableOption = {
		responsive: true,
		lengthMenu: [5, 10, 20, 30, 40, 50],
		pageLength: 20
	};
	
	const _S3CRETST0RE = {};
	
	/**
	 * get store
	 */
	function getStore() {
		return _S3CRETST0RE;
	}
</script>

<script src="../common-scripts/consts.js" type="text/javascript"></script>
<script src="../common-scripts/common.js" type="text/javascript"></script>