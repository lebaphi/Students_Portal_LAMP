<script src="./new-user/user.script.js" type="text/javascript"></script>
<div class="fade-in">
	<div class="kt-subheader kt-grid__item" id="kt_subheader">
		<div class="kt-subheader__main">
			<h3 class="kt-subheader__title">User Management</h3>
			<div class="kt-subheader__breadcrumbs">
				<a href="javascript:;" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
				<span class="kt-subheader__breadcrumbs-separator"></span>
				<a href="javascript:;" onclick="reload()" class="kt-subheader__breadcrumbs-link">
					User Management</a>
			</div>
		</div>
		<div class="kt-subheader__toolbar">
			<div class="kt-subheader__wrapper">
			
			</div>
		</div>
	</div>
	
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		<div class="alert alert-light alert-elevate" role="alert">
			<div class="alert-icon"><i class="flaticon-info kt-font-brand"></i></div>
			<div class="alert-text">
				Description will be place here
			</div>
		</div>
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label align-middle-center">All Users
					<span class="kt-switch kt-switch--sm kt-switch--outline kt-switch--icon kt-switch--success kt-margin-l-5 kt-margin-t-15">
						<label>
							<input type="checkbox" onclick="loadAllUser(this.checked)">
							<span></span>
						</label>
					</span>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<a href="javascript:;" id="btnAddNewUser" class="btn btn-secondary btn-elevate btn-icon-sm none-display" data-toggle="kt-tooltip" title="Add User" onclick="showUserPanel()">
								<i class="la la-plus"></i>
								New User
							</a>
						</div>
					</div>
				</div>
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
	<div id="kt_quick_panel_toggler_btn" class="kt-header__topbar-icon"></div>
	
	<?php
		require_once '../../form-ui/form/user.forms.php';
		require_once '../../form-ui/form/client.forms.php';
	?>
	
</div>
