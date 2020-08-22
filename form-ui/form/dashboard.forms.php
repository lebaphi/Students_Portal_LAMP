<button type="button" class="none-display" id="viewFileFormModal" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#viewFileFormTarget"></button>
<div class="modal fade" id="viewFileFormTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form>
				<div class="modal-header">
					<h4 name="viewModalTitle"></h4>
					<select class="form-control col-xl-2 col-md-2 col-lg-2" id="scaleFile" onchange="scale(this.getAttribute('fType'), this.selectedOptions[0].value)">
						<option selected disabled>Scale</option>
						<option value="0.25">25%</option>
						<option value="0.5">50%</option>
						<option value="0.75">75%</option>
						<option value="1">100%</option>
					</select>
					<a class="btn btn-secondary btn-elevate no-border kt-margin-l-5" style="color: blue;" data-toggle="kt-tooltip" title="Print" id="openPrintPage" onclick="renderPdfPrinting()"><i
								class="la la-print"></i></a>
					<a class="btn btn-secondary btn-elevate no-border kt-margin-l-5" style="color: blue;" data-toggle="kt-tooltip" title="Copy link" id="copiedLink" onclick="copyClipboard(this.value)"><i
								class="la la-link"></i></a>
					<a class="btn btn-secondary btn-elevate no-border kt-margin-l-5" style="color: blue;" data-toggle="kt-tooltip" title="Download" id="downloadedLink"><i
								class="la la-download"></i></a>
					
					<div class="form-inline kt-margin-l-5" id="pdfSection">
						<a class="btn btn-secondary btn-elevate no-border la la-step-backward disabled" name="previousPageBtn" href="javascript:;" data-toggle="kt-tooltip" title="Previous Page"
						   onclick="goPreviousPage()"></a>
						<input type="number" name="pdfCurrentPage" class="form-control kt-margin-l-5" style="width: 50px;" oninput="goToPage(parseInt(this.value))"/>
						<div class="form-control no-border" disabled style="width: 10px;">of</div>
						<div class="form-control no-border" disabled style="width: 20px;" name="pdfNumPages"></div>
						<a class="btn btn-secondary btn-elevate no-border la la-step-forward kt-margin-l-5" name="nextPageBtn" href="javascript:;" data-toggle="kt-tooltip" title="Next Page"
						   onclick="goNextPage()"></a>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="onFileFormModalClose()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="overflow-auto align-middle-center" style="min-height: 300px;">
						<div style="margin-left: 42%;margin-top: 10%; position: absolute;">
							<div id="loadingIndicator"></div>
						</div>
						<img id="srcImg"/>
						<canvas id="srcPdf"></canvas>
						<iframe id="srcWord" class="word"></iframe>
						<div id="filledForm" style="text-align: left;"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="exportPdfBtn" autofocus onclick="exportPDF()">
						<i class="la la-download"></i>
						Export PDF
					</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="onFileFormModalClose()">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="loadFileFormModal" data-backdrop="static" data-toggle="modal" data-target="#loadFileFormTarget"></button>
<div class="modal fade" id="loadFileFormTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form onsubmit="return false;">
				<div class="modal-header">
					<table>
						<thead>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>Name</td>
							<td><input type="text" class="form-control kt-margin-l-5" id="filled_form_name" name="filled_form_name"/></td>
						</tr>
						<tr>
							<td>Template</td>
							<td><span class="capitalize kt-margin-l-5 kt-font-bold" name="loadFormName"></span></td>
						</tr>
						<tr>
							<td>User</td>
							<td><span class="kt-margin-l-5 kt-font-bold"><?php echo $currentUser->isGhostUser() ? $currentUser->getGhostName() :$currentUser->getDisplayName(); ?></span></td>
						</tr>
						</tbody>
					</table>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeForm()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="overflow-auto">
						<div id="mainFormModal"></div>
					</div>
				</div>
				<div class="modal-footer" style="align-items: baseline;">
					<div class="none-display" style="display: flex;" id="save_form_group">
						<div class="kt-margin-t-10">
							<span>Save As</span>
						</div>
						<div>
							<select class="form-control kt-margin-r-5 kt-margin-l-5" style="width: 200px;" id="container_location" name="container_location" onchange="chooseContainer(this.selectedOptions[0].value)">
								<option value="-1" disabled selected>Select container</option>
							</select>
						</div>
						<div>
							<select class="form-control kt-margin-r-5" style="width: 200px;" id="sub_container_location" name="sub_container_location" onchange="chooseSubContainer(this.selectedOptions[0].value)">
								<option value="-1" disabled selected>Select sub container</option>
							</select>
						</div>
					</div>
					<button type="button" class="btn btn-primary" id="save_filled_form_btn" name="btnSaveForm" onclick="saveForm(this)">Save</button>
					<button type="button" class="btn btn-secondary" id="close_filled_form_btn" data-dismiss="modal" onclick="closeForm()">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="uploadFileFormModal" data-backdrop="static" data-toggle="modal" data-target="#uploadFileFormTarget"></button>
<div class="modal fade" id="uploadFileFormTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form>
				<div class="modal-header">
					<h4>Upload</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetUploadedForm()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php require_once "../component/list.uploading.files.php"; ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetUploadedForm()">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="editUserProfileModal" data-backdrop="static" data-toggle="modal" data-target="#editUserProfileTarget"></button>
<div class="modal fade" id="editUserProfileTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form onsubmit="updateUserProfile(this); return false;">
				<div class="modal-header">
					<h4>Edit User Profile</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetForm(this)">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">Avatar</label>
						<div class="col-lg-9 col-xl-6">
							<div class="kt-avatar kt-avatar--outline kt-avatar--circle">
								<img style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" id="user_avatar">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">Title</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control" type="text" name="title" placeholder="Title">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">First Name</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control" type="text" name="first_name" placeholder="First Name">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">Last Name</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control" type="text" name="last_name" placeholder="Last Name">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">Address</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control" type="text" name="address" placeholder="Address">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">Company Name</label>
						<div class="col-lg-9 col-xl-6">
							<input class="form-control" type="text" name="company" placeholder="Company">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">Contact Phone</label>
						<div class="col-lg-9 col-xl-6">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
								<input type="text" class="form-control" name="phone" placeholder="Phone" aria-describedby="basic-addon1">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-xl-3 col-lg-3 col-form-label kt-align-right">Email Address</label>
						<div class="col-lg-9 col-xl-6">
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
								<input type="text" disabled class="form-control" name="email" placeholder="Email" aria-describedby="basic-addon1">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id">
					<input type="hidden" name="action" value="updateProfile">
					<button type="button" class="btn btn-brand btn-elevate" onclick="updateUserProfile(this)">Save</button>
					<button type="button" class="btn btn-secondary btn-elevate" data-dismiss="modal" onclick="resetForm(this)">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="updateCourseUserStatus" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#updateCourseUserStatusTarget"></button>
<div class="modal fade" id="updateCourseUserStatusTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form onsubmit="setStatusCourseUser(this); return false;">
				<div class="modal-header">
					<h4>Set Course Status</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetCourseUserForm(this)">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row kt-margin-l-10">
						<label class="col-form-label">Start Date</label>
						<div class="col-lg-6 col-md-9 col-sm-12 kt-margin-l-10">
							<input type="text" name="course_start" class="form-control" placeholder="Select date" id="course_user_start_date" onclick="handleDatePickerPosition()" onchange="setDate(this.value, 0)"/>
						</div>
					</div>
					<div class="form-group row kt-margin-l-10">
						<label class="col-form-label">End Date</label>
						<div class="col-lg-6 col-md-9 col-sm-12 kt-margin-l-20">
							<select class="form-control" name="course_end" onchange="setDate(this.selectedOptions[0].value, 1)">
								<option selected disabled>Select expire date</option>
								<option value="0">0</option>
								<option value="1">1 year</option>
								<option value="2">2 years</option>
								<option value="3">3 years</option>
								<option value="4">4 years</option>
								<option value="5">5 years</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" id="closeCourseUserModal" class="none-display"></button>
					<button type="button" class="btn btn-secondary btn-elevate" data-dismiss="modal" onclick="resetCourseUserForm(this)">Close</button>
					<button type="button" class="btn btn-brand btn-elevate" onclick="setStatusCourseUser(this)">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="editCourseUser" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#editCourseUserTarget"></button>
<div class="modal fade" id="editCourseUserTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<form onsubmit="return false;">
				<div class="modal-header">
					<h4>Edit</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetPreviousStatus()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<a href="javascript:;" onclick="editCourseDate()" class="btn btn-secondary"><i class="la la-edit"></i> Edit Course</a>
					<a href="javascript:;" onclick="expireCourse()" class="btn btn-danger"><i class="flaticon-cancel"></i> Set Expire</a>
				</div>
				<div class="modal-footer">
					<button data-dismiss="modal" id="closeEditCourseModal" class="none-display"></button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="userTerm" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#userTermTarget"></button>
<div class="modal fade" id="userTermTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form>
				<div class="modal-header" style="padding-bottom: 0;">
					<div>
						<h4 style="margin-bottom: 0;">Term & Condition</h4>
						<span class="form-text text-muted" style="font-size: smaller;">Last Update: October 14, 2019</span>
					</div>
				</div>
				<div class="modal-body" style="max-height: 500px; overflow-y: auto;">
					<h5>Privacy Policy</h5>
					<p>T360, Tickner 360, Tickner & Associates Inc. ("us", "we", or "our") operates the portal.ticknersafety.com, website (the "Service").</p>
					<p>This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service and the choices you have associated with that data.</p>
					<p>We use your data to provide and improve the Service. By using the Service, you agree to the collection and use of information in accordance with this policy. Unless otherwise defined in this Privacy Policy, terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, accessible from portal.ticknersafety.com</p>
					
					<br>
					<h5>Information Collection And Use</h5>
					<p>We collect several different types of information for various purposes to provide and improve our Service to you.</p>
					
					<br>
					<h5>Types of Data Collected</h5>
					
					<br>
					<h5>Company Data</h5>
					<p>While using our service, we may ask you to provide us with certain information about your companie(s), or other companies that can be used to contact or identify them. Please ensure you have proper approval when providing such information.</p>
					
					<br>
					<h5>Personal Data</h5>
					<p>While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you ("Personal Data"). Personally identifiable information may include, but is not limited to:</p>
					<ul>
						<li>Email address</li><li>First name and last name</li><li>Phone number</li><li>Address, State, Province, ZIP/Postal code, City</li><li>Personal Details</li><li>Cookies and Usage Data</li>
					</ul>
					
					<br>
					<h5>Usage Data</h5>
					<p>We may also collect information how the Service is accessed and used ("Usage Data"). This Usage Data may include information such as your computer's Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that you visit, the time and date of your visit, the time spent on those pages, unique device identifiers and other diagnostic data.</p>
					
					<br>
					<h5>Tracking & Cookies Data</h5>
					<p>We use cookies and similar tracking technologies to track the activity on our Service and hold certain information.</p>
					<p>Cookies are files with small amount of data which may include an anonymous unique identifier. Cookies are sent to your browser from a website and stored on your device. Tracking technologies also used are beacons, tags, and scripts to collect and track information and to improve and analyze our Service.</p>
					<p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>
					<p>Examples of Cookies we use:</p>
					<ul>
						<li><strong>Session Cookies.</strong> We use Session Cookies to operate our Service.</li>
						<li><strong>Preference Cookies.</strong> We use Preference Cookies to remember your preferences and various settings.</li>
						<li><strong>Security Cookies.</strong> We use Security Cookies for security purposes.</li>
					</ul>
					
					<br>
					<h5>Use of Data</h5>
					<p>T360 uses the collected data for various purposes:</p>
					<ul>
						<li>To provide and maintain the Service</li>
						<li>To notify you about changes to our Service</li>
						<li>To allow you to participate in interactive features of our Service when you choose to do so</li>
						<li>To provide customer care and support</li>
						<li>To provide analysis or valuable information so that we can improve the Service</li>
						<li>To monitor the usage of the Service</li>
						<li>To detect, prevent and address technical issues</li>
					</ul>
					
					<br>
					<h5>Transfer Of Data</h5>
					<p>Your information, including Personal Data, may be transferred to — and maintained on — computers located outside of your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from your jurisdiction.</p>
					<p>If you are located outside Canada and choose to provide information to us, please note that we transfer the data, including Personal Data, to Canada and process it there.</p>
					<p>Your consent to this Privacy Policy followed by your submission of such information represents your agreement to that transfer.</p>
					<p>T360 will take all steps reasonably necessary to ensure that your data is treated securely and in accordance with this Privacy Policy and no transfer of your Personal Data will take place to an organization or a country unless there are adequate controls in place including the security of your data and other personal information.</p>
					
					<br>
					<h5>Disclosure Of Data</h5>
					
					<br>
					<h5>Legal Requirements</h5>
					<p>T360 may disclose your Personal Data in the good faith belief that such action is necessary to:</p>
					<ul>
						<li>To comply with a legal obligation</li>
						<li>To protect and defend the rights or property of T360</li>
						<li>To prevent or investigate possible wrongdoing in connection with the Service</li>
						<li>To protect the personal safety of users of the Service or the public</li>
						<li>To protect against legal liability</li>
					</ul>
					
					<br>
					<h5>Security Of Data</h5>
					<p>The security of your data is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.</p>
					
					<br>
					<h5>Service Providers</h5>
					<p>We may employ third party companies and individuals to facilitate our Service ("Service Providers"), to provide the Service on our behalf, to perform Service-related services or to assist us in analyzing how our Service is used.</p>
					<p>These third parties have access to your Personal Data only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</p>
					
					<br>
					<h5>Analytics</h5>
					<p>We may use internal and/or third-party Service Providers to monitor and analyze the use of our Service.</p>
					
					<br>
					<h5>Links To Other Sites</h5>
					<p>Our Service may contain links to other sites that are not operated by us. If you click on a third party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit.</p>
					<p>We have no control over and assume no responsibility for the content, privacy policies or practices of any third party sites or services. This also includes the malicious acts that may occur.</p>
					
					<br>
					<h5>Children's Privacy</h5>
					<p>Our Service does not address anyone under the age of 18 ("Children").</p>
					<p>We do not knowingly collect personally identifiable information from anyone under the age of 18. If you are a parent or guardian and you are aware that your Children has provided us with Personal Data, please contact us. If we become aware that we have collected Personal Data from children without verification of parental consent, we take steps to remove that information from our servers.</p>
					
					<br>
					<h5>Changes To This Privacy Policy</h5>
					<p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>
					<p>We will let you know via email and/or a prominent notice on our Service, prior to the change becoming effective and update the "effective date" at the top of this Privacy Policy.</p>
					<p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
					
					<br>
					<h5>Contact Us</h5>
					<p>If you have any questions about this Privacy Policy, please contact us:</p>
					<ul>
						<li>By email: help@ticknersafety.com</li>
					</ul>
					<p>By using our services, you agree to our policy. You also understand that we are not responsible for your data and provide no gurentee of any backups. </p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success btn-elevate" data-dismiss="modal" onclick="handleTermCondition(1)">Agree</button>
					<button type="button" class="btn btn-danger btn-elevate" data-dismiss="modal" onclick="handleTermCondition(0)">Disagree</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="uploadFileUrl" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#uploadFileUrlTarget"></button>
<div class="modal fade" id="uploadFileUrlTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form onsubmit="uploadUrl(this); return false;">
				<div class="modal-header">
					<h4>Upload File Url</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="onCloseFormUrl(this)">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="fileUrlBody" style="max-height: 400px; overflow-y: auto;">
					<div style="display: flex; justify-content: flex-start; margin-top: 2px;">
						<input class="form-control" name="file_url" placeholder="Input file url here" value="" oninput="validateStringUrl(this)"/>
						<a href="javascript:;" onclick="addFileUrl()" class="btn btn-secondary" style="padding: 2px 0 2px 2px; margin: 5px 1px 5px 5px;">
							<i class="la la-plus"></i>
						</a>
						<a href="javascript:;" onclick="removeFileUrl(this)" class="btn btn-secondary" style="padding: 2px 0 2px 2px; margin: 5px 5px 5px 0;">
							<i class="la la-minus"></i>
						</a>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-elevate" data-dismiss="modal" id="closeUploadUrlModal" onclick="onCloseFormUrl(this)">Cancel</button>
					<button type="button" class="btn btn-brand btn-elevate" name="uploadUrlBtnName" onclick="uploadUrl(this)">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="addTrainingUserModal" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#addTrainingUserTarget"></button>
<div class="modal fade" id="addTrainingUserTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<form onsubmit="addTrainingUser(this); return false;">
				<div class="modal-header">
					<h4>Add Training User</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetForm(this)">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<input type="text" class="form-control" placeholder="First Name" name="first_name"/>
					<input type="text" class="form-control kt-margin-t-5" placeholder="Last Name" name="last_name"/>
					<input type="text" class="form-control kt-margin-t-5" placeholder="Email" name="email"/>
					<input type="password" class="form-control kt-margin-t-5" placeholder="Password" name="password"/>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="client_id">
					<input type="hidden" name="action" value="addTrainingUserAction">
					<button type="button" class="btn btn-secondary btn-elevate" data-dismiss="modal" onclick="resetForm(this)">Cancel</button>
					<button type="button" class="none-display" data-dismiss="modal" id="closeTrainingUserForm"></button>
					<button type="button" class="btn btn-brand btn-elevate" onclick="addTrainingUser(this)">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="capFormModal" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#capFormModalTarget"></button>
<div class="modal fade" id="capFormModalTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<form onsubmit="return false;">
				<div class="modal-header kt-padding-b-0">
					<div style="display: flex; flex-direction: column;">
						<h4 style="margin-bottom: 0;">C.A.P</h4>
						<span class="form-text text-muted" name="capUser"></span>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="onCloseCAPForm()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="capFieldBody" style="max-height: 400px; overflow-y: auto;">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-elevate" data-dismiss="modal" id="closeCAPForm" onclick="onCloseCAPForm()">Cancel</button>
					<button type="button" class="btn btn-brand btn-elevate" onclick="addCAPForm()">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="moveFileFormModal" data-backdrop="static" data-toggle="modal" data-target="#moveFileFormTarget"></button>
<div class="modal fade" id="moveFileFormTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form onsubmit="return false;">
				<div class="modal-header">
					<h4>Move</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetForm(this)">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="flex-box kt-margin-t-10">
						<div style="width: 49%">
							<label class="form-control-label">Project <span class="required">*</span></label>
							<select class="form-control" id="move_project_id" name="project_id" onchange="onSelectProject(this.selectedOptions[0].value, 'move_site_id', true, 'container_id')" disabled>
								<option value="-1" selected disabled>Select project</option>
							</select>
						</div>
						<div style="width: 49%">
							<label class="form-control-label"="">Site</label>
							<select class="form-control" id="move_site_id" name="site_id" disabled>
								<option value="-1" selected disabled>Select site</option>
								<option value="-1">None</option>
							</select>
						</div>
					</div>
					<div class="flex-box kt-margin-t-10">
						<div style="width: 49%;">
							<label class="form-control-label">Container <span class="required">*</span></label>
							<select class="form-control" id="move_container_id" name="container_id" onchange="onSelectContainer(this.selectedOptions[0].value, 'move_sub_container_id')">
								<option value="-1" selected disabled>Select container</option>
							</select>
						</div>
						<div style="width: 49%;">
							<label class="form-control-label">Sub-Container</label>
							<select class="form-control" id="move_sub_container_id" name="sub_container_id">
								<option value="-1" selected disabled>Select sub container</option>
								<option value="-1">None</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="f_id">
					<input type="hidden" name="f_type">
					<input type="hidden" name="action" value="moveFileFormLocation">
					<button type="button" class="btn btn-secondary btn-elevate" data-dismiss="modal" id="closeMoveFileModal" onclick="resetForm(this)">Cancel</button>
					<button type="button" class="btn btn-brand btn-elevate" onclick="moveFileForm(this)">Move</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="createClientContainer" data-backdrop="static" data-toggle="modal" data-target="#createClientContainerTarget"></button>
<div class="modal fade" id="createClientContainerTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form onsubmit="return false;">
				<div class="modal-header">
					<h4>New Container</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetAssignClientContainerForm(this);">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<label class="form-control-label kt-margin-t-10">Parent container<span class="required">*</span></label>
					<select class="form-control" name="client_container_parent" id="client_container_parent" onchange="setContainerStyle(this.selectedOptions[0].value)">
						<option value="-1" selected>Root Folder</option>
					</select>
					<label class="form-control-label kt-margin-t-10">Container name <span class="required">*</span></label>
					<input class="form-control kt-input" type="text" name="client_container_name" autocomplete="off">
					<label class="form-control-label kt-margin-t-10">Description <span class="required">*</span></label>
					<textarea class="form-control kt-input" maxlength="500" rows="2" name="client_container_description"></textarea>
					<label class="form-control-label kt-margin-t-10" id="client_container_icon_label">Icon <span class="required">*</span></label>
					<select class="form-control" name="client_container_icon" id="client_container_icon">
						<option selected disabled>Select icon</option>
						<option value="icon1">Icon 1</option>
						<option value="icon2">Icon 2</option>
						<option value="icon3">Icon 3</option>
					</select>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="client_id">
					<input type="hidden" name="container_style">
					<input type="hidden" name="container_parent_id">
					<input type="hidden" name="container_id">
					<input type="hidden" name="action" value="createClientContainer">
					<button data-dismiss="modal" class="btn btn-secondary" id="closeCreateClientContainerModal" onclick="resetAssignClientContainerForm(this)">Cancel</button>
					<button type="button" class="btn btn-brand btn-elevate" onclick="createContainerClient(this)">Create</button>
				</div>
			</form>
		</div>
	</div>
</div>

<button type="button" class="none-display" id="removeCustomContainerModal" data-backdrop="static" data-toggle="modal" data-target="#removeCustomContainerModalTarget"></button>
<div class="modal fade" id="removeCustomContainerModalTarget" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form onsubmit="return false;">
				<div class="modal-header">
					<h4>Remove Container</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetForm(this)">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-striped table-hover table-checkable" id="listCustomContainers">
						<thead>
							<tr>
								<th></th>
								<th>Name</th>
								<th>Description</th>
								<th>Created Date</th>
								<th>Style</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="custom_container_data">
					<input type="hidden" name="action" value="removeCustomContainer">
					<button type="button" class="btn btn-secondary btn-elevate" id="closeRemoveCustomContainerBtn" onclick="resetForm(this)" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-brand btn-elevate" name="removeCustomContainerBtn" disabled onclick="removeContainerClient(this)">Remove</button>
				</div>
			</form>
		</div>
	</div>
</div>