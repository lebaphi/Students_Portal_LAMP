<script src="../form-ui/components-scripts/footer.bar.script.js" type="text/javascript"></script>
<div class="kt-footer  kt-footer--extended  kt-grid__item" id="kt_footer" style="background-image: url('../assets/media/bg/bg-2.jpg');">
	<div class="kt-footer__bottom">
		<div class="kt-container">
			<div class="kt-footer__wrapper">
				<div class="kt-footer__logo">
					<a class="kt-header__brand-logo" href="javascript:;">
						<img alt="Logo" src="../assets/media/logos/default-sm.png" class="kt-header__brand-logo-sticky">
					</a>
					<div class="kt-footer__copyright">
						<span>V <?php echo $appVersion;?></span>&nbsp;
						<a href="javascript:;">2020&nbsp;&copy; Compnay Name - All Rights Reserved</a>
					</div>
				</div>
				<div class="kt-footer__menu" style="cursor: pointer;">
					<a onclick="openPolicy();">Privacy Policy</a>
					<a onclick="openTerm();">Terms</a>
					<a onclick="contactUs();">Contact Us</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	require_once '../form-ui/form/footer.forms.php';
?>