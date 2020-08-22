<script src="../assets/vendors/custom/webfont/webfont.js"></script>
<script src="../assets/vendors/global/vendors.bundle.js" type="text/javascript"></script>
<script src="../assets/js/scripts.bundle.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/jquery-ui/jquery-ui.bundle.js" type="text/javascript"></script>

<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
<script src="../assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/gmaps/gmaps.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/datatables/dataTables.rowReorder.min.js" type="text/javascript"></script>
<script src="../assets/js/pages/crud/datatables/extensions/buttons.js" type="text/javascript"></script>
<script src="../assets/js/pages/components/extended/sweetalert2.js" type="text/javascript"></script>
<script src="../assets/js/pages/components/extended/toastr.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/jstree/jstree.bundle.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/moment/moment.js" type="text/javascript"></script>
<script src="../assets/vendors/custom/cryptoJs/aes.js" type="text/javascript"></script>

<script src="../assets/vendors/custom/pdf/pdf.js"></script>
<script src="../assets/vendors/custom/datepicker/bootstrap-datepicker.js"></script>
<script src="../assets/vendors/custom/surveyjs/survey.jquery.min.js"></script>
<script src="../assets/vendors/custom/surveyjs/knockout-min.js"></script>
<script src="../assets/vendors/custom/surveyjs/survey.ko.min.js"></script>
<script src="../assets/vendors/custom/surveyjs/jspdf.min.js"></script>
<script src="../assets/vendors/custom/surveyjs/survey.pdf.min.js"></script>
<script src="../assets/vendors/custom/surveyjs/ace.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../assets/vendors/custom/surveyjs/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<script src="../assets/vendors/custom/surveyjs/signature_pad.min.js"></script>
<script src="../assets/vendors/custom/surveyjs/jquery.barrating.js"></script>
<script src="../assets/vendors/custom/surveyjs/surveyjs-widgets.js"></script>
<script src="../assets/vendors/custom/surveyjs/survey-creator.js"></script>

<script src="../assets/vendors/custom/statistic-chart/core.js"></script>
<script src="../assets/vendors/custom/statistic-chart/charts.js"></script>
<script src="../assets/vendors/custom/statistic-chart/animated.js"></script>

<script src="../assets/js/pages/dashboard.js" type="text/javascript"></script>
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