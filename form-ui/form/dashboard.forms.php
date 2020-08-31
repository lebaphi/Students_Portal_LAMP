<button type="button" class="none-display" id="openStudentModal" data-backdrop="static" data-toggle="modal" data-target="#openStudentTarget"></button>
<div class="modal fade" id="openStudentTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form id="add_student_form" return false;">
				<div class="modal-header">
					<h4>New Student</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Gender</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<select class="form-control" name="gender">
								<option selected disabled>Select gender</option>
								<option value="male">Male</option>
								<option value="female">Female</option>
								<option value="other">Other</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Religion</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="religion" placeholder="Religion">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Nationality</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="nationality" placeholder="Nationality">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Residence Country</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="residence_country" placeholder="Residence Country">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Residence City</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="residence_city" placeholder="Residence City">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Data</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="data" placeholder="Data">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Cell Number</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="tel" name="cell_number" placeholder="Cell Number">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Email</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="email" name="email" placeholder="Email">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Computer</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="computer" placeholder="Computer">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">English</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="english" placeholder="English">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Last Degree</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="last_degree" placeholder="Last Degree">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Education Level</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="education_level" placeholder="Education Level">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Specialization</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="specialization" placeholder="Specialization">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Current Inst level</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="inst_level_name" placeholder="Current Inst level">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Inst Name</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="inst_name" placeholder="Instname">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Grade Name</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="grade_name" placeholder="Grade Name">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Course</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="course" placeholder="Course">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Remarks by AKEB</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="remarks_by_akeb" placeholder="Remarks by AKEB">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Academic Year</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="academic_year" placeholder="Academic Year">
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<input type="hidden" name="action" value="add_student">
					<input type="hidden" id="action_mode" name="mode">
					<button type="button" class="btn btn-brand btn-elevate" onclick="addStudent(this)">Add</button>
					<button type="button" class="btn btn-secondary btn-elevate" id="btnCloseModal" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="openUserProfileModal" data-backdrop="static" data-toggle="modal" data-target="#openUserProfileTarget"></button>
<div class="modal fade" id="openUserProfileTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form id="profile_user_form" onsubmit="saveUserProfile(this); return false;">
				<div class="modal-header">
					<h4>User Profile</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Title</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="title" placeholder="Title">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">First Name</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="first_name" placeholder="First Name">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Last Name</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="last_name" placeholder="Last Name">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Address</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="address" placeholder="Address">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">City</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="city" placeholder="City">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Province/State</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="province" placeholder="Province">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Country</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="country" placeholder="Country">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Company Name</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<input class="form-control" type="text" name="company" placeholder="Company">
						</div>
					</div>
					<div class="form-group row" id="user_section_edit_role">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">User Role</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<select class="form-control" name="role">
								<option value="user">User</option>
								<?php
									if ($currentUser->isAdmin()){
										echo '<option value="admin">Admin</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">User Status</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<select class="form-control" name="deleted">
								<option value="0">Active</option>
								<option value="1">Inactive</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Contact Phone</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
								<input type="text" class="form-control" name="phone" placeholder="Phone" aria-describedby="basic-addon1">
							</div>
							<span class="form-text text-muted">We'll never share your email with anyone else.</span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-md-3 col-form-label kt-align-right">Email Address</label>
						<div class="col-lg-9 col-xl-6 col-md-9">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
								<input type="text" disabled class="form-control" name="email" placeholder="Email" aria-describedby="basic-addon1">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="action" value="updateUser">
					<button type="button" class="btn btn-brand btn-elevate" onclick="saveUserProfile(this)">Update</button>
					<button type="button" class="btn btn-secondary btn-elevate" id="btnCloseUpdateUserInfo" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>