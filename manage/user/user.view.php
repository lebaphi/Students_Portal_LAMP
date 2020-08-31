<script src="./user/user.script.js" type="text/javascript"></script>
<div class="fade-in">
	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-margin-t-40" id="kt_content">
		<div class="alert alert-light alert-elevate" role="alert">
			Manage Area
		</div>
		
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label align-middle-center"></div>
				
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="user_table">
					<thead>
					<tr>
						<td style="width: 5%;">No.</td>
						<td style="width: 5%;">Type</td>
						<th style="width: 30%;">Email</th>
						<th style="width: 15%;">First Name</th>
						<th style="width: 15%;">Last Name</th>
						<th style="width: 10%;min-width: 120px;">Role</th>
						<th style="width: 10%; min-width: 100px;">Last Login</th>
						<th style="width: 5%;">Status</th>
						<th style="width: 5%;">Actions</th>
						<th></th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	
	<?php
		require_once '../../form-ui/form/dashboard.forms.php';
	?>

</div>