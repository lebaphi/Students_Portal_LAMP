$(document).ready(function () {
	const user_table = getElmByIdJQuery('user_table').DataTable({
		...tableOption,
		autoWidth: false,
		data: [],
		columnDefs: [
			{ targets: [0, 1, 8], orderable: false },
			{ targets: [9], visible: false },
			{ orderData: [9], targets: [6] },
		],
		columns: [
			{ data: 'index' },
			{ data: 'iconElm' },
			{ data: 'email' },
			{ data: 'firstNameElm' },
			{ data: 'lastNameElm' },
			{ data: 'roleElm' },
			{ data: 'lastLoggedIn' },
			{ data: 'status' },
			{ data: 'actionElm' },
			{ data: 'last_logged_in' },
		],
		language: {
			emptyTable: 'No user',
		},
	})
	//handle No. column after sort
	user_table
		.on('order.dt search.dt', function () {
			user_table
				.column(0, { search: 'applied', order: 'applied' })
				.nodes()
				.each(function (cell, i) {
					cell.innerHTML = i + 1
				})
		})
		.draw()
	
	setRouteVariable(USER_ROUTE, { user_table })
	loadAllUser()
})

/**
 *
 * @param softUser
 */
function loadAllUser() {
	const form = buildMessageForm({
		action: 'getAllUsers',
	})
	sendRequest(form, {
		url: './user/user.php',
		method: 'GET',
	}, (response) => {
		if (isSuccess(response)) {
			const user_table = getRouteVariable('user', 'user_table')
			const processedData = response.data.map((user, idx) => {
				const {
					id,
					first_name,
					last_name,
					email,
					role,
					last_logged_in,
					deleted,
					current_user_role,
				} = user
				const iconElm = '<i class="flaticon-user kt-font-success" style="font-size: x-large;" data-toggle="kt-tooltip" title="User allows to login"></i>'
				const statusElm =
					deleted === 0
						? '<span class="kt-badge kt-badge--success kt-badge--inline" style="display: initial">Active</span>'
						: '<span class="kt-badge kt-badge--danger kt-badge--inline" style="display: initial;">Inactive</span>'
				const lastLoggedIn = last_logged_in
					? moment.utc(last_logged_in).local().fromNow()
					: 'Not login'
				const firstNameElm = '<span>' + first_name + '</span>'
				const lastNameElm = '<span>' + last_name + '</span>'
				const roleElm = '<span class="capitalize">' + role + '</span>'
				const status = '<span>' + statusElm + '</span>'
				
				let actionElm = ''
				if ([ADMIN].includes(current_user_role)) {
					actionElm +=
						'<a class="dropdown-item" href="javascript:;" onclick="editUserProfile(\'' +
						encodeURIComponent(JSON.stringify(user)) +
						'\')"><i class="flaticon-edit button-icon"></i>Edit profile</a>'
					const currentUser = getRouteVariable(COMMON, 'currentUser')
					setElementVisible(
						'createUserFromClient',
						currentUser && currentUser.role === ADMIN
					)
					if (current_user_role === ADMIN) {
						actionElm +=
							'<a class="dropdown-item" href="javascript:;" onclick="removeUser(' +
							id +
							')"><i class="flaticon-delete button-icon"></i>Remove</a>'
					}
				}
				actionElm = wrapMenuAction(actionElm)
				return {
					index: idx + 1,
					iconElm,
					email,
					firstNameElm,
					lastNameElm,
					roleElm,
					lastLoggedIn,
					status,
					actionElm,
					last_logged_in,
				}
			})
			
			reDrawTable(user_table, processedData)
			KTApp.initTooltips()
		} else {
			toastr.error(response.msg)
		}
	})
}

/**
 *
 * @param userId
 */
function removeUser(userId) {
	const form = buildMessageForm({
		user_id: userId,
		action: 'removeUser',
	})
	formConfirm(
		'Remove user',
		'Are you sure?',
		form,
		{
			url: './user/user.php',
			method: 'POST',
		},
		(response) => {
			if (isSuccess(response)) {
				loadAllUser()
				toastr.success(response.msg)
			} else {
				toastr.error(response.msg)
			}
		}
	)
}

/**
 *
 * @param userId
 */
function editUserProfile(userInfo) {
	const userSrc = JSON.parse(decodeURIComponent(userInfo))
	const form = getElmById('profile_user_form')
	toArray(form.elements).map((elm) => {
		if (elm.name !== 'action') {
			elm.value = userSrc[elm.name]
		}
	})
	addFieldToForm(form, 'user_id', userSrc.id)
	addFieldToForm(form, 'user_email', userSrc.email)
	const currentUser = getRouteVariable(COMMON, 'currentUser')
	setElementVisible(
		'user_section_edit_role',
		currentUser && currentUser.role === 'admin' && currentUser.id !== userSrc.id
	)
	triggerButton('openUserProfileModal')
}

/**
 *
 * @param elm
 */
function saveUserProfile(elm) {
	const form = $(elm).closest('form')
	form.validate({
		rules: {
			first_name: {
				required: true,
			},
			last_name: {
				required: true,
			},
			role: {
				required: true,
			},
			status: {
				required: true,
			},
		},
	})
	
	if (!form.valid()) {
		return
	}
	
	triggerButton('btnCloseUpdateUserInfo')
	sendRequest(form, {
		url: './user/user.php',
		method: 'POST'
	}, (response) => {
		if (isSuccess(response)) {
			loadAllUser()
			toastr.success(response.msg)
		} else {
			toastr.error(response.msg)
		}
	})
}