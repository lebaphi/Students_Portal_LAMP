/**
 * support show pass option
 * @param isShow
 */
function showPass(isShow) {
	const elm = document.getElementsByName('password')[0];
	if (isShow) {
		elm.setAttribute('type', 'text');
	} else {
		elm.setAttribute('type', 'password');
	}
}

/**
 * add new user
 */
function addNewUser(elm) {
	const form = $(elm).closest('form');
	form.validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			firstName: {
				required: true,
			},
			lastName: {
				required: true,
			},
			role: {
				required: true
			},
			status: {
				required: true
			}
		}
	});
	
	if (!form.valid()) {
		return;
	}
	
	updateElementStatus('addNewUserBtn', false);
	KTApp.blockPage({
		overlayColor: '#000000',
		type: 'v2',
		state: 'success',
		message: 'Please wait...'
	});
	
	sendRequest(form, {
		url: '../manage/new-user/user.php',
		method: 'POST'
	}, (response) => {
		updateElementStatus('addNewUserBtn', true);
		KTApp.unblockPage();
		if (isSuccess(response)) {
			triggerButton('kt_demo_panel_close');
			if (isElmVisible('user_table')) {
				loadAllUser(false);
			}
			if (isElmVisible('client_user_table')) {
				loadClientUserTable();
			}
			toastr.success(response.msg);
		} else {
			toastr.error(response.msg);
		}
	})
}

/**
 * add new client
 */
function addNewClient(elm) {
	const form = $(elm).closest('form');
	form.validate({
		rules: {
			coName: {
				required: true,
			},
			coShortName: {
				required: true,
			},
			coAddress: {
				required: true,
			},
			coPhone: {
				required: true,
			},
			coEmail: {
				required: true,
				email: true
			},
			coStatus: {
				required: true,
			}
		}
	});
	
	if (!form.valid()) {
		return;
	}
	
	updateElementStatus('addNewClientBtn', false);
	KTApp.blockPage({
		overlayColor: '#000000',
		type: 'v2',
		state: 'success',
		message: 'Please wait...'
	});
	
	sendRequest(form, {
		url: '../manage/new-client/client.php',
		method: 'POST'
	}, (response) => {
		updateElementStatus('addNewClientBtn', true);
		KTApp.unblockPage();
		if (isSuccess(response)) {
			triggerButton('kt_demo_panel_close');
			loadClientList();
			toastr.success(response.msg);
		} else {
			toastr.error(response.msg);
		}
	});
}

/**
 *
 * @param checked
 */
function onResetClientLogo(checked) {
	updateElementStatus('btnAddLogo', !checked);
}