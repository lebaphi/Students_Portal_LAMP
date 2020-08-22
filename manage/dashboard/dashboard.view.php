<script src="./dashboard/dashboard.script.js" type="text/javascript"></script>
<div class="fade-in">
	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-margin-t-40" id="kt_content">
		<div class="alert alert-light alert-elevate" role="alert">
			Manage Area
		</div>
		
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label align-middle-center"></div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
							<a href="javascript:;" id="btnAddNewStudent" class="btn btn-secondary btn-elevate btn-icon-sm" data-toggle="kt-tooltip" title="Add Student" onclick="showAddStudentModal()">
								<i class="la la-plus"></i>
								New Student
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				
				<!--begin: Datatable -->
				<table class="table table-striped table-hover table-checkable" id="student_table">
					<thead>
					<tr>
						<td style="width: 5%;"></td>
						<td style="width: 5%;">Gender</td>
						<th style="width: 15%;">Email</th>
						<th style="width: 10%;">Nationality</th>
						<th style="width: 10%;">Last Degree</th>
						<th style="width: 15%;">Education Level</th>
						<th style="width: 10%;">Grade Name</th>
						<th style="width: 10%;">Course</th>
						<th style="width: 15%;">Academic Year</th>
						<th style="width: 5%;">Actions</th>
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