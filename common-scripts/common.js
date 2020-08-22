/**
 *
 * @param routeName
 * @param object
 */
function setRouteVariable(routeName, object) {
	getStore()[routeName] = setReadOnlyObject(object);
}

/**
 *
 * @param routeName
 * @param variableName
 * @returns {*}
 */
function getRouteVariable(routeName, variableName) {
	return (getStore()[routeName] && getStore()[routeName][variableName]) || undefined;
}

/**
 *
 * @param routeName
 * @param key
 * @param value
 * @returns {{value: *, key: *}}
 */
function updateRouteVariable(routeName, key, value) {
	return getStore()[routeName][UPDATE_PROPERTY] = {key, value};
}

/**
 * set read only for object
 * @param object
 */
function setReadOnlyObject(object) {
	let readOnlyObj = {};
	readOnlyObj = new Proxy(readOnlyObj, {
		setProperty: function (target, key, value) {
			if (target.hasOwnProperty(key)) {
				console.error('Uncaught TypeError: Assignment to constant variable.');
				return target[key];
			}
			return target[key] = value;
		},
		get: function (target, key) {
			return target[key];
		},
		set: function (target, key, value) {
			if (key === UPDATE_PROPERTY) {
				target[value.key] = value.value;
			} else {
				return this.setProperty(target, key, value);
			}
		},
		defineProperty: function (target, key, value) {
			return this.setProperty(target, key, value);
		},
		deleteProperty: function () {
			return false;
		}
	});
	for (let prop in object) {
		readOnlyObj[prop] = object[prop];
	}
	return readOnlyObj;
}

/**
 * validate editing row
 * @param elm
 * @param rowId
 * @param value
 * @returns {boolean}
 */
function validateRow(elm, rowId, value) {
	let rowValid = true;
	if (value.length === 0) {
		elm.style.borderColor = 'red';
		elm.valid = false;
		toastr.error('This field is required');
	} else {
		elm.style.borderColor = 'blue';
		elm.valid = true;
	}
	const rowElm = elm.parentElement.parentElement;
	const inputElms = [...rowElm.getElementsByTagName('input')];
	for (const inputElm of inputElms) {
		if (false === inputElm.valid) {
			rowValid = false;
		}
	}
	updateElementStatus(`save_${rowId}`, rowValid);
	return rowValid;
}

/**
 * update field value on change
 * @param fieldId
 * @param value
 * @param required
 * @param elm
 */
function updateField(fieldId, value, required, elm) {
	if (!fieldId.includes('expiry_')) {
		if (required && !validateRow(elm, fieldId.split('_')[1], value)) {
			return;
		}
		getElmById(fieldId).value = value;
	} else if (fieldId.includes('expiry_')) {
		getElmById(fieldId).value = value[0];
		const rowId = fieldId.split('_')[1];
		let status = 'active';
		if (new Date(value) < new Date(moreVal)) {
			status = 'expired';
		}
		updateField('status_' + rowId, status);
	} else if (fieldId.includes('status_')) {
		const rowId = fieldId.split('_')[1];
		const publishedDate = getElmValueById('published_' + rowId);
		const expiryDate = getElmValueById('expiry_' + rowId);
		if (value === 'active' && new Date(publishedDate) > new Date(expiryDate)) {
			toastr.error("Please update 'Expiry Date' field for changing the status!");
			getElmById('selectedStatus_' + rowId).value = 'expired';
		}
	}
}

/**
 * form confirm information
 * @param title
 * @param msgText
 * @param form
 * @param options
 * @param cb
 */
function formConfirm(title, msgText, form, options, cb) {
	swal.fire({
		title: title,
		text: msgText,
		type: 'info',
		showCancelButton: true,
		confirmButtonText: 'Yes'
	}).then(function (result) {
		if (result.value) {
			if (form && options && cb && result.value) {
				sendRequest(form, options, cb)
			} else if (cb) {
				cb(result.value);
			}
		}
	});
}

/**
 * clean all value form when submit success
 */
function cleanFormValue(form) {
	let formElm = form;
	if (typeof form === 'string') {
		formElm = getElmByIdJQuery(form);
	}
	for (const input of formElm[0].elements) {
		if (input.type) {
			input.removeAttribute('value');
		}
	}
}

/**
 * send request to server
 * @param form element or form elm id
 * @param options
 * @param cb
 */
function sendRequest(form, options, cb) {
	let formElm = form;
	if (typeof form === 'string') {
		formElm = getElmByIdJQuery(form);
	}
	
	formElm.ajaxSubmit({
		url: options.url,
		type: options.method,
		success: function (response) {
			if (!options.keepFormData) {
				resetForm(formElm);
			}
			try {
				cb(JSON.parse(response));
			} catch (err) {
				cb(JSON.parse("{\"result\": \"failed\", \"msg\": \"Unexpected error\"}"));
			}
		}
	});
}

/**
 * load date time picker
 * @param pickerId
 */
function loadDateTimePicker(pickerId) {
	getElmByIdJQuery(pickerId).datetimepicker({
		todayHighlight: !0,
		autoclose: !0,
		pickerPosition: "bottom-left",
		format: "yyyy-mm-dd hh:ii:ss"
	})
}

/**
 * encode base64
 * @param value
 * @returns {string}
 */
function encode64(value) {
	return btoa(`${value}#s!2-Ec*5=R3@t`);
}

/**
 * decode base64
 * @param encodeId
 * @returns {string}
 */
function decode64(encodeId) {
	const decodeId = atob(encodeId);
	return decodeId.substr(0, decodeId.indexOf('#'));
}

/**
 * get checked elements in the table
 * @param elmName
 * @returns {...NodeListOf<HTMLElement>[]}
 */
function getSelectedCheckboxByName(elmName) {
	const selectUsersCbx = [...document.getElementsByName(elmName)];
	return selectUsersCbx.filter(item => item.checked);
}

/**
 * update button status on check
 * @param buttonNames
 * @param cbxName
 */
function onChecked(buttonNames, cbxName) {
	const checked = getSelectedCheckboxByName(cbxName);
	for (const buttonName of buttonNames) {
		updateElementStatus(buttonName, checked.length > 0);
	}
}

/**
 * update button status by element or element name
 * @param element | element name
 * @param enabled
 */
function updateElementStatus(element, enabled) {
	let elms = [element];
	if (typeof element === 'string') {
		elms = [...document.getElementsByName(element)];
	}
	for (const elm of elms) {
		let classElm = elm.getAttribute('class') || '';
		if (enabled) {
			classElm = classElm.replace(/disabled/g, '').trim();
			elm.removeAttribute('disabled')
		} else {
			if (!classElm.includes('disabled')) {
				classElm += ' disabled';
			}
			elm.setAttribute('disabled', 'true');
		}
		elm.setAttribute('class', classElm);
	}
}

/**
 *
 * @param object
 */
function updateElementStatusObject(object) {
	for (let key in object) {
		updateElementStatus(key, object[key]);
	}
}

/**
 *
 * @param object
 */
function setElementVisibleObject(object) {
	for (let key in object) {
		setElementVisible(key, object[key]);
	}
}

/**
 * set element visibility
 * @param element or elementId
 * @param visible
 */
function setElementVisible(element, visible) {
	let elm = element;
	if (typeof element === 'string') {
		elm = getElmById(element);
	}
	if (visible) {
		$(elm).show();
	} else {
		$(elm).hide();
	}
}

/**
 *
 * @param elmName
 * @param visible
 */
function setElementVisibleByName(elmName, visible) {
	const elms = getElmsByName(elmName);
	toArray(elms).map(elm => {
		elm.style.display = visible ? 'initial' : 'none';
	})
}

/**
 *
 * @param page
 * @param objectArgs
 * @param cb
 */
function toRoute(page, objectArgs, cb) {
	let options = {action: 'renderPage'};
	if (objectArgs) {
		options = {...options, ...objectArgs};
	}
	loadPage(page, options, cb || (() => {
	}));
}

/**
 * load page
 * @param page
 * @param options
 * @param cb
 */
function loadPage(page, options, cb) {
	prepareToLoad();
	const mainDiv = $('div#mainContents');
	KTApp.blockPage({
		overlayColor: '#000000',
		type: 'v2',
		state: 'success',
		message: 'Please wait...'
	});
	
	mainDiv.load(page, options, (response, statusString) => {
		KTApp.init(KTAppOptions);
		KTAppUserProfile.init();
		KTApp.unblockPage();
		if (statusString === 'error') {
			cb(null);
		} else {
			cb(response);
		}
	});
}

/**
 * prepare to load page
 */
function prepareToLoad() {
	const openingTooltips = document.getElementsByClassName('tooltip fade');
	for (const openingTooltip of openingTooltips) {
		openingTooltip.parentNode.removeChild(openingTooltip);
	}
}

/**
 * initialize quick panel
 */
function initQuickPanel() {
	const demoPanel = getElmById('kt_demo_panel');
	const offcanvas = new KTOffcanvas(demoPanel, {
		overlay: true,
		baseClass: 'kt-demo-panel',
		closeBy: 'kt_demo_panel_close',
		toggleBy: 'kt_demo_panel_toggle'
	});
	
	const head = KTUtil.find(demoPanel, '.kt-demo-panel__head');
	const body = KTUtil.find(demoPanel, '.kt-demo-panel__body');
	
	KTUtil.scrollInit(body, {
		disableForMobile: true,
		resetHeightOnDestroy: true,
		handleWindowResize: true,
		height: function () {
			let height = parseInt(KTUtil.getViewPort().height);
			
			if (head) {
				height = height - parseInt(KTUtil.actualHeight(head));
				height = height - parseInt(KTUtil.css(head, 'marginBottom'));
			}
			
			height = height - parseInt(KTUtil.css(demoPanel, 'paddingTop'));
			height = height - parseInt(KTUtil.css(demoPanel, 'paddingBottom'));
			
			return height;
		}
	});
	
	if (typeof offcanvas !== 'undefined') {
		offcanvas.on('hide', function () {
			const expires = new Date(new Date().getTime() + 60 * 60 * 1000); // expire in 60 minutes from now
			Cookies.set('kt_panel_shown', 1, {expires: expires});
		});
		
		offcanvas.on('afterHide', () => {
			if (isElmVisible('m_add_user_section')) {
				const userForm = getElmByIdJQuery('user_client').closest('form');
				resetForm(userForm);
				for (let i = 0; i < userForm[0].elements.length; i++) {
					const elm = userForm[0].elements[i];
					if (['firstName', 'lastName', 'role', 'status'].includes(elm.name)) {
						$(elm).rules("add", {
							required: true,
						});
					}
					if (elm.name === 'email') {
						$(elm).rules("add", {
							required: true,
							email: true
						});
					}
				}
			}
			if (isElmVisible('m_add_client_section')) {
				const clientForm = getElmByIdJQuery('coConsultant').closest('form');
				resetForm(clientForm);
				for (let i = 0; i < clientForm[0].elements.length; i++) {
					const elm = clientForm[0].elements[i];
					if (['coName', 'coShortName', 'coAddress', 'coPhone'].includes(elm.name)) {
						$(elm).rules("add", {
							required: true,
						});
					}
					if (elm.name === 'coEmail') {
						$(elm).rules("add", {
							required: true,
							email: true
						});
					}
				}
			}
		})
	}
}

/**
 * close modal by dismiss-modal-button-id
 * @param btnId
 */
function closeModal(btnId) {
	triggerButton(btnId);
	return new Promise(resolve => {
		setTimeout(() => {
			resolve();
		}, 300)
	})
}

/**
 * reset form
 * @param form
 */
function resetForm(form) {
	if ('BUTTON' === form.tagName) {
		form = $(form).closest('form');
	}
	form.clearForm();
	const validate = form.validate();
	if (validate) {
		validate.resetForm();
	}
}

/**
 *
 * @param isTerm
 */
function checkShowTerm(isTerm) {
	if (!isTerm) {
		setTimeout(() => {
			triggerButton('userTerm');
		}, 3000);
	}
}

/**
 * handle user term action
 * @param isAgree
 */
function handleTermCondition(isAgree) {
	const form = buildMessageForm({
		user_agree: isAgree,
		action: 'handleUserTerm'
	});
	sendRequest(form, {
		url: './new-user/user.php',
		method: 'POST'
	}, (response) => {
		if (!isSuccess(response)) {
			setTimeout(() => {
				location.reload();
			}, 1000);
		}
	})
}

/**
 *
 * @param elmId
 * @param value
 */
function setElmValueById(elmId, value) {
	getElmById(elmId).value = value;
}

/**
 *
 * @param menuElm
 */
function wrapMenuAction(menuElm) {
	let actionElm =
		'<span class="dropdown">' +
		'<a href="javascript:;" class="btn btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">' +
		'<i class="la la-ellipsis-h"></i>' +
		'</a>' +
		'<div class="dropdown-menu dropdown-menu-right">';
	actionElm += menuElm;
	actionElm += '</div></span>';
	return actionElm;
}

/**
 * set property value of element by object elms
 * @param object
 */
function setElmValuePropByElmNameObject(object) {
	for (let key in object) {
		setPropertyByElmName(key, 'value', object[key]);
	}
}

/**
 * set property value of element by element name
 * @param elmName
 * @param value
 */
function setElmValuePropByElmName(elmName, value) {
	setPropertyByElmName(elmName, 'value', value);
}

/**
 * set property innerText of element by element name
 * @param elmName
 * @param text
 */
function setElmTextByElmName(elmName, value) {
	setPropertyByElmName(elmName, 'innerText', value);
}

/**
 *
 * @param elmName
 * @param value
 */
function setElmInnerHtmlByElmName(elmName, value) {
	setPropertyByElmName(elmName, 'innerHTML', value);
}

/**
 * set property name of element by element name
 * @param elmName
 * @param value
 */
function setElmNamePropByElmName(elmName, value) {
	setPropertyByElmName(elmName, 'name', value);
}

/**
 * set element property
 * @param element
 * @param propertyName
 * @param propertyValue
 */
function setPropertyByElmName(elmName, propertyName, propertyValue) {
	[...document.getElementsByName(elmName)].map(elm => {
		elm[propertyName] = propertyValue;
	});
}

/**
 * get client id
 * @returns {string}
 */
function getClientId() {
	return getRouteVariable(COMMON, 'clientId');
}

/**
 *
 * @param clientId
 */
function storeClientId(clientId) {
	updateRouteVariable(COMMON, 'clientId', clientId);
}

/**
 * click the button by button id
 * @param buttonId
 */
function triggerButton(buttonId) {
	getElmById(buttonId).click();
}

/**
 * get element value by element id
 * @param elmId
 * @returns {*}
 */
function getElmValueById(elmId) {
	return getElmById(elmId).value;
}

/**
 * get element by id
 * @param elmId
 * @returns {HTMLElement}
 */
function getElmById(elmId) {
	return document.getElementById(elmId);
}

/**
 *
 * @param elmId
 * @returns {jQuery|HTMLElement}
 */
function getElmByIdJQuery(elmId) {
	return $(`#${elmId}`);
}

/**
 *
 * @param tagName
 * @param elmName
 * @returns {jQuery|HTMLElement}
 */
function getElmsByNameJQuery(tagName, elmName) {
	return $(`${tagName}[name="${elmName}"]`);
}

/**
 * get element by class name
 * @param className
 */
function getElmByClassName(className) {
	return document.getElementsByClassName(className)[0];
}

/**
 * get all element by class name
 * @param className
 */
function getElmsByClassName(className) {
	return document.getElementsByClassName(className);
}

/**
 *
 * @param elmName
 * @returns {NodeListOf<HTMLElement>}
 */
function getElmsByName(elmName) {
	return document.getElementsByName(elmName);
}

/**
 * create element
 * @param tagName
 * @returns {any}
 */
function createElm(tagName) {
	return document.createElement(tagName);
}

/**
 *
 * @param form
 * @param fieldName
 * @param fieldValue
 * @returns {*}
 */
function addFieldToForm(form, fieldName, fieldValue) {
	const field = createElm('input');
	field.setAttribute('type', 'hidden');
	field.setAttribute('name', fieldName);
	field.setAttribute('value', fieldValue);
	form.append(field);
}

/**
 * check if file is pdf
 * @param fileName
 */
function isPdf(fileName) {
	return fileName.endsWith('.pdf');
}

/**
 * check file is image
 * @param fileName
 * @returns {*|boolean}
 */
function isImageFile(fileName) {
	return fileName.endsWith('.jpg') || fileName.endsWith('.jpeg') || fileName.endsWith('.png');
}

/**
 * check file is word
 * @param fileName
 * @returns {*}
 */
function isDocumentFile(fileName) {
	return fileName.endsWith('.doc') || fileName.endsWith('.docx') || fileName.endsWith('.txt');
}

/**
 * block component
 * @param componentId
 */
function blockComponent(componentId) {
	KTApp.block(`#${componentId}`, {
		overlayColor: '#000000',
		type: 'v2',
		state: 'success',
		message: 'Please wait...'
	});
}

/**
 * unclock component
 * @param componentId
 */
function unblockComponent(componentId) {
	KTApp.unblock(`#${componentId}`);
}

/**
 *
 * @param arrayIds
 * @param type
 * @param extraData
 */
function loadAssignClientsModal(arrayIds, type, extraData) {
	setElmValuePropByElmNameObject({
		f_id: JSON.stringify(arrayIds),
		f_type: type
	});
	const form = buildMessageForm({
		action: 'getAvailableClients'
	});
	sendRequest(form, {
		url: './new-file/file.php',
		method: 'GET'
	}, (response) => {
		let listClient = [];
		if (isSuccess(response)) {
			listClient = JSON.parse(response.data).map(item => {
				const {company_name} = item;
				item['name'] = company_name;
				return item;
			});
		}
		renderCombobox('s_client_id', listClient);
		cleanCombobox('project_id');
		cleanCombobox('site_id');
		if (extraData) {
			for (let key in extraData) {
				if (key === 'save_mode') {
					setElementVisible('form_location', extraData[key] !== MANUAL_FORM);
				}
				setElmValuePropByElmName(key, extraData[key]);
			}
		}
		setElmTextByElmName('f_title', type === 'form' ? 'Add Form To Client' : 'Add File To Client');
		setElementVisible('change_default_form', type === 'form');
		triggerButton('addFileFormToClient');
	})
}

/**
 * first load for client side
 * @param clientId
 * @param projectElmId
 * @param siteElmId
 * @param autoLoad
 */
function onSelectClient(clientId, projectElmId, siteElmId, autoLoad) {
	storeClientId(encode64(clientId));
	updateRouteVariable(COMMON, 'p_s_mapping', []);
	const form = buildMessageForm({
		clientId: getClientId(),
		action: 'getProjectsClients'
	});
	sendRequest(form, {
		url: './new-client/client.php',
		method: 'GET'
	}, function (response) {
		if (isSuccess(response)) {
			updateRouteVariable(COMMON, 'p_s_mapping', response.data);
			const listProject = response.data.map(item => item.project);
			updateRouteVariable(COMMON, 'currentListProjects', listProject);
			renderCombobox(projectElmId, listProject);
			cleanCombobox(siteElmId, []);
			const projectClient = response.data.map(item => item.project);
			const siteClient = response.data.reduce((acc, item) => {
				item.site.map(siteItem => acc.push(siteItem));
				return acc;
			}, []);
			
			if (autoLoad) {
				setElementVisibleObject({
					project_site_table: projectClient.length > 1 || siteClient.length > 1,
					project_id: projectClient.length > 1 || siteClient.length > 1,
					site_id: projectClient.length > 1
				});
				updateElementStatusObject({
					project_id: projectClient.length > 1,
					site_id: siteClient.length > 1,
					'btn-home': response.data.length > 0
				});
			}
			
			if (response.data.length === 0) {
				const form = buildMessageForm({
					clientId: getClientId(),
					action: 'getAllClientContainer'
				});
				sendRequest(form, {
					url: './new-dashboard/dashboard.php',
					method: 'GET'
				}, (response) => {
					if (isSuccess(response)) {
						renderContainerUI(response.data, true);
					} else {
						toastr.error(response.msg);
					}
				})
			} else if (autoLoad) {
				const projectElm = getElmByIdJQuery(projectElmId);
				projectElm.prop("selectedIndex", 1);
				const projectId = projectElm.children("option:selected").val();
				selectProject(projectId);
				if (projectClient.length === 1 && siteClient.length === 1) {
					const siteElm = getElmByIdJQuery(siteElmId);
					siteElm.prop("selectedIndex", 1);
					const siteId = siteElm.children("option:selected").val();
					selectSite(siteId);
				}
			}
		} else {
			toastr.error(response.msg);
		}
	});
}

/**
 *
 * @param projectId
 * @param siteElmId
 * @param assignMode
 * @param containerElmId
 * @param cb
 */
function onSelectProject(projectId, siteElmId, assignMode, containerElmId, cb = () => {
}) {
	const sites = getRouteVariable(COMMON, 'p_s_mapping').reduce((sites, item) => {
		if (`${item.project.id}` === projectId) {
			sites = [...item.site];
		}
		return sites;
	}, []);
	renderCombobox(siteElmId, sites);
	if (sites.length === 0) {
		getElmByIdJQuery(siteElmId).val(-1);
	}
	if (assignMode) {
		const form = buildMessageForm({
			clientId: getClientId(),
			action: 'getMainContainers'
		});
		sendRequest(form, {
			url: './new-program/program.php',
			method: 'POST'
		}, (response) => {
			renderCombobox(containerElmId, response.data);
			cb(true);
		});
	} else {
		cb(true);
	}
}

/**
 * load sub container
 * @param containerId
 * @param subContainerElmId of element
 */
function onSelectContainer(containerId, subContainerElmId) {
	const form = buildMessageForm({
		container_id: containerId,
		sub_container_id: -1,
		action: 'getContainers'
	});
	sendRequest(form, {
		url: './new-program/program.php',
		method: 'GET'
	}, (response) => {
		if (isSuccess(response)) {
			const containers = JSON.parse(response.data.json);
			renderCombobox(subContainerElmId, containers);
			if (containers.length === 0) {
				getElmByIdJQuery(subContainerElmId).val(-1);
			}
		} else {
			toastr.error(response.msg);
		}
	})
}

/**
 * clean combobox
 * @param elm
 */
function cleanCombobox(elmId) {
	const elm = getElmById(elmId);
	const oldOptions = [...elm.getElementsByTagName('option')];
	for (const oldOption of oldOptions) {
		if (oldOption.value !== '-1' && oldOption.value !== 'none') {
			oldOption.parentElement.removeChild(oldOption);
		}
	}
	getElmByIdJQuery(elmId).prop('selectedIndex', 0);
}

/**
 * render combobox by combobox id and data
 * @param elmId
 * @param projects
 */
function renderCombobox(elmId, data) {
	const selectElm = getElmById(elmId);
	cleanCombobox(elmId);
	for (const item of data) {
		const option = createElm('option');
		selectElm.appendChild(option);
		option.outerHTML = '<option value="' + item.id + '">' + item.name + '</option>';
	}
	updateElementStatus(elmId, data.length > 0);
}

/**
 *
 * @param form
 * @returns {*}
 */
function encodeClientIdForm(form) {
	for (let i = 0; i < form[0].elements.length; i++) {
		if (form[0].elements[i].name === 'client_id') {
			form[0].elements[i].selectedOptions[0].value = getClientId();
		}
	}
	return form;
}

/**
 * handle assign file | form to client
 * @param elm
 */
function addFileForm(elm, cbFunc) {
	const form = $(elm).closest('form');
	form.validate({
		ignore: ":hidden",
		rules: {
			form_name: {
				required: true,
			},
			client_id: {
				required: true,
			},
			project_id: {
				required: true,
			},
			container_id: {
				required: true,
			}
		}
	});
	
	if (!form.valid()) {
		return;
	}
	
	closeModal('closeModalProgramFileForm').then(() => {
		sendRequest(encodeClientIdForm(form), {
			url: './new-file/file.php',
			method: 'POST'
		}, cbFunc);
	});
}

/**
 * close alert of form validation error
 * @param form
 */
function closeAlert(form) {
	const alert = form.find('.alert');
	alert.fadeOut('fast', () => {
		$('#closeAlertBtn').click();
	});
}

/**
 * show validation message on form
 * @param form
 * @param type
 * @param msg
 */
const showMsg = function (form, type, msg) {
	const alert = $('<div class="kt-margin-5 kt-alert kt-alert--outline alert alert-' + type + ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closeAlertBtn"></button><span></span></div>');
	form.find('.alert').remove();
	alert.prependTo(form);
	KTUtil.animateClass(alert[0], 'fadeIn animated');
	alert.find('span').html(msg);
}

/**
 * append waiting indicator to the button
 * @param btn
 * @param isWaiting
 */
const setWaiting = function (btn, isWaiting) {
	if (isWaiting) {
		btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', isWaiting);
	} else {
		btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', isWaiting);
	}
}

/**
 * set file | form favorite
 * @param file/form id
 */
function setFavorite(elm) {
	elm.value = !JSON.parse(elm.value);
	const url = elm.getAttribute('ftype') === FILE_TYPE ?
		'./new-file/file.php' :
		'./new-form-viewer/form.viewer.php';
	const form = buildMessageForm({
		f_id: elm.getAttribute('fid'),
		f_favorite: elm.value,
		f_type: elm.getAttribute('ftype'),
		action: 'setFavoriteFileForm'
	});
	sendRequest(form, {
		url: url,
		method: 'POST'
	}, (response) => {
		if (isSuccess(response)) {
			elm.setAttribute('class', (elm.value === 'true') ? 'no-border fa fa-star checked' : 'no-border fa fa-star');
			toastr.success(response.msg);
		} else {
			toastr.error(response.msg);
		}
	});
}

/**
 * capitalize input string
 * @param str
 */
function capitalize(str) {
	return `${str.charAt(0).toUpperCase()}${str.substring(1)}`
}

/**
 *
 * @param number
 * @param property
 * @returns {string}
 */
function pluralize(number, property) {
	return `${number} ${property}${number !== 1 ? 's' : ''}`
}

/**
 * get file type
 * @param file
 * @returns {*}
 */
function getFileType(file) {
	return file.name.split('.').pop();
}

/**
 * check file is image
 * @param file
 * @returns {boolean}
 */
function isImage(file) {
	return file.type.indexOf('image') !== -1;
}

/**
 *
 * @param files
 * @param isClient
 * @returns {Promise<any>[]}
 */
function processTableData(files, isClient) {
	const result = [...files].map((file) => {
		return new Promise(resolve => {
			const reader = new FileReader();
			reader.onload = function (e) {
				let fileType = getFileType(file);
				if (!['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'].includes(fileType)) {
					fileType = 'file';
				}
				if (fileType === 'docx') fileType = 'doc';
				let srcImg = `../assets/media/files/${fileType}.svg`;
				if (isImage(file)) {
					srcImg = e.target.result;
				}
				
				const fileSize = parseInt(file.size / 1024);
				let description = '';
				let ignore = false;
				if (fileSize / 1024 > 2) {
					description = 'This file exceeded the file size limitation (2MB)';
					ignore = true;
				}
				const uploadingFiles = getRouteVariable(COMMON, 'uploadingFiles');
				const idx = uploadingFiles.length;
				uploadingFiles.push({idx, 'name': `${UUID.generate()}##${file.name}`, ignore, file});
				updateRouteVariable(COMMON, 'uploadingFiles', uploadingFiles);
				const addFileDescriptionClass = ignore ? 'btn kt-padding-r-0 disabled' : 'btn kt-padding-r-0';
				const checkStraight = '<div class="kt-margin-l-15" id="straightCbxP_' + idx + '"><label class="kt-checkbox kt-checkbox--solid kt-checkbox--success kt-checkbox-override"><input type="checkbox" name="childStraightCbx" cbx-idx="' + idx + '" onclick="onStraight(this.checked, ' + idx + ')"><span></span></label></label></div>';
				const imgReview = '<img src="' + srcImg + '" class="img-thumbnail" style="width: 50%"/></td>';
				const progressBar = '<div class="flex-box"><div class="progress kt-margin-t-15" style="width: 100%;"><div id="uploadProgressBar_' + idx + '" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%"></div></div><div class="kt-align-left flex-row"><a name="btnDescription" class="' + addFileDescriptionClass + '" title="Add description" onclick="showDescriptionModal(' + idx + ')"><i class="flaticon-plus"></i></a><a name="btnRemoveFile" class="btn" title="Remove"><i class="flaticon-delete"></i></a></div></div>';
				const straightSetting = '<div class="flex-row"><button id="straightSetting_' + idx + '" class="btn btn-secondary none-display" onclick="openStraightSetting(' + idx + ')"><i class="flaticon-cogwheel"></i></button><i id="straightStatus_' + idx + '" class="la la-check-circle button-icon set-success kt-margin-l-5 kt-margin-t-10 none-display"></i><span class="kt-margin-l-10" name="fileDesc_' + idx + '" style="font-style: italic;"> ' + description + '</span></div>';
				const tableRow = [isClient || ignore ? '' : checkStraight, file.name, imgReview, `${fileSize} KB`, progressBar, isClient ? '<span class="kt-margin-l-10" name="fileDesc_' + idx + '" style="font-style: italic;"></span>' : straightSetting];
				resolve(tableRow);
			}
			reader.readAsDataURL(file);
		});
	});
	return result;
}

/**
 *
 * @param checked
 */
function selectAllUploadingFile(checked) {
	const allCbx = getElmsByName('childStraightCbx');
	toArray(allCbx).map(cbx => {
		const idx = cbx.getAttribute('cbx-idx') || 0;
		cbx.checked = checked;
		onStraight(checked, idx);
	})
}

/**
 *
 * @param fileIdx
 */
function openStraightSetting(fileIdx) {
	setElmValueById('fileIdx', fileIdx);
	triggerButton('straightAssignBtn');
}

/**
 *
 * @param clientId
 */
function assignStraightClient(clientId) {
	onSelectClient(clientId, `drop_straight_project`, `drop_straight_site`);
	const form = buildMessageForm({
		clientId: encode64(clientId),
		action: 'getMainContainers'
	});
	sendRequest(form, {
		url: './new-program/program.php',
		method: 'POST'
	}, (response) => {
		renderCombobox('drop_straight_container', response.data);
	});
}

/**
 *
 * @param projectId
 */
function assignStraightProject(projectId) {
	onSelectProject(projectId, `drop_straight_site`);
}

/**
 *
 * @param containerId
 */
function assignStraightContainer(containerId) {
	onSelectContainer(containerId, 'drop_straight_sub_container');
}

/**
 *
 * @param tagName
 * @param elmId
 * @param elmName
 * @param elmValue
 * @returns {any}
 */
function buildChild(tagName, elmId, elmName, elmValue) {
	const elm = createElm(tagName);
	elm.setAttribute('type', 'hidden');
	elm.setAttribute('id', elmId);
	elm.setAttribute('name', elmName);
	if (elmValue) {
		elm.setAttribute('value', elmValue);
	}
	return elm;
}

/**
 * build form Upload
 * @param fileIdx
 * @param isStraight
 * @returns {any}
 */
function buildFormUpload(fileIdx, isStraight) {
	const formElm = createElm('form');
	formElm.setAttribute('id', `upload_${fileIdx}`);
	formElm.setAttribute('class', 'none-display');
	
	const straightCbx = buildChild('input', `straightCbx_${fileIdx}`, 'straightCbx');
	straightCbx.setAttribute('value', isStraight);
	straightCbx.setAttribute('idx', fileIdx);
	const straightFileName = buildChild('input', `straightFileName_${fileIdx}`, 'straightFileName');
	const straightClient = buildChild('input', `straightClient_${fileIdx}`, 'straightClient');
	const straightProject = buildChild('input', `straightProject_${fileIdx}`, 'straightProject');
	const straightSite = buildChild('input', `straightSite_${fileIdx}`, 'straightSite');
	const straightContainer = buildChild('input', `straightContainer_${fileIdx}`, 'straightContainer');
	const straightSubContainer = buildChild('input', `straightSubContainer_${fileIdx}`, 'straightSubContainer');
	const straightActionName = buildChild('input', `straightAction`, 'action', 'straightAction');
	
	formElm.appendChild(straightCbx);
	formElm.appendChild(straightFileName);
	formElm.appendChild(straightClient);
	formElm.appendChild(straightProject);
	formElm.appendChild(straightSite);
	formElm.appendChild(straightContainer);
	formElm.appendChild(straightSubContainer);
	formElm.appendChild(straightActionName);
	
	return formElm;
}

/**
 * remove form elm
 * @param formId
 */
function removeFormUpload(formId) {
	const formElm = getElmById(formId);
	formElm.parentElement.removeChild(formElm);
}

/**
 *
 * @param checked
 * @param idx
 */
function onStraight(checked, idx) {
	setElementVisible(`straightSetting_${idx}`, checked);
	let formUpload = getElmById(`upload_${idx}`);
	if (!formUpload) {
		formUpload = buildFormUpload(idx, checked);
		getElmById(`straightCbxP_${idx}`).appendChild(formUpload);
	} else {
		setElmValueById(`straightCbx_${idx}`, checked);
	}
	const checkedStraights = toArray(getElmsByName('straightCbx')).filter(cbxElm => cbxElm.value === 'true');
	const batchFileUploadingBtns = getRouteVariable(FILE_ROUTE, 'batchFileUploadingBtns');
	setElementVisible(batchFileUploadingBtns[0], checkedStraights.length > 1);
	updateElementStatus(batchFileUploadingBtns[0], checkedStraights.length > 1);
	
	if (checked) {
		const form = buildMessageForm({
			action: 'getAvailableClients'
		});
		sendRequest(form, {
			url: './new-file/file.php',
			method: 'GET'
		}, (response) => {
			let listClient = [];
			if (isSuccess(response)) {
				listClient = JSON.parse(response.data).map(item => {
					const {company_name} = item;
					item['name'] = company_name;
					return item;
				});
			} else {
				toastr.error(response.msg);
			}
			renderCombobox('drop_straight_client', listClient);
			cleanCombobox('drop_straight_project');
			cleanCombobox('drop_straight_site');
		})
	} else {
		cleanCombobox('drop_straight_client');
		setElementVisible(`straightStatus_${idx}`, false);
		getElmById(`straightStatus_${idx}`).removeAttribute('isSetup');
		toArray(formUpload.elements).forEach(elm => {
			if (!['straightFileName', 'action'].includes(elm.name)) {
				elm.removeAttribute('value')
			}
		});
	}
}

/**
 *
 * @param cbxId
 * @param innerHTML
 */
function resetCombobox(cbxId, innerHTML) {
	const cbxElm = getElmById(cbxId);
	cbxElm.innerHTML = innerHTML;
}

/**
 *
 * @param elm
 */
function updateSetting(elm) {
	const form = $(elm).closest('form');
	form.validate({
		rules: {
			drop_straight_client: {
				required: true,
			},
			drop_straight_project: {
				required: true
			},
			drop_straight_container: {
				required: true
			}
		}
	});
	
	if (!form.valid()) {
		return;
	}
	const batchStraightAssignFlag = getRouteVariable(COMMON, 'batchStraightAssignFlag');
	if (!batchStraightAssignFlag) {
		const idx = getElmValueById('fileIdx');
		setStraightSettingByIdx(idx);
	} else {
		const checkedStraights = toArray(getElmsByName('straightCbx')).filter(cbxElm => cbxElm.value === 'true');
		checkedStraights.map(cbxElm => {
			const idx = cbxElm.getAttribute('idx');
			setStraightSettingByIdx(idx);
		});
		updateRouteVariable(COMMON, 'batchStraightAssignFlag', false);
	}
	triggerButton('closeStraightModal');
	resetForm(form);
	resetCombobox('drop_straight_project', '<option value="-1" disabled selected> Select Project</option>');
	resetCombobox('drop_straight_site', '<option value="-1" disabled selected> Select Site</option>');
	resetCombobox('drop_straight_container', '<option value="-1" disabled selected> Select Container</option>');
	resetCombobox('drop_straight_sub_container', '<option value="-1" disabled selected> Select Sub Container</option>');
}

/**
 *
 * @param idx
 */
function setStraightSettingByIdx(idx) {
	const uploadingFiles = getRouteVariable(COMMON, 'uploadingFiles');
	const selectedFile = uploadingFiles.filter(item => `${item.idx}` === idx);
	setElmValueById(`straightFileName_${idx}`, selectedFile[0].name);
	setElmValueById(`straightClient_${idx}`, encode64(getElmValueById('drop_straight_client')));
	setElmValueById(`straightProject_${idx}`, getElmValueById('drop_straight_project'));
	setElmValueById(`straightSite_${idx}`, getElmValueById('drop_straight_site'));
	setElmValueById(`straightContainer_${idx}`, getElmValueById('drop_straight_container'));
	setElmValueById(`straightSubContainer_${idx}`, getElmValueById('drop_straight_sub_container'));
	highlightButton(`straightSetting_${idx}`, false);
	setElementVisible(`straightStatus_${idx}`, true);
	getElmById(`straightStatus_${idx}`).setAttribute('isSetup', 'true');
}

/**
 *
 * @param buttonId
 * @param hasError
 */
function highlightButton(buttonId, hasError) {
	const button = getElmById(buttonId);
	const buttonClass = button.getAttribute('class');
	if (hasError) {
		button.setAttribute('class', `${buttonClass} error-field`);
	} else {
		button.setAttribute('class', `${buttonClass.replace(/error-field/g, '')}`);
	}
}

/**
 *
 * @param table
 * @param files
 * @param isClient
 * @returns {Promise<void>}
 */
async function renderListFile(table, files, isClient) {
	const data = await Promise.all(processTableData(files, isClient));
	const uploadingFiles = getRouteVariable(COMMON, 'uploadingFiles');
	const enableUploadFile = uploadingFiles.filter(item => !item.ignore);
	const colName = isClient || !enableUploadFile.length ? '' : '<div class="kt-margin-l-15"><label class="kt-checkbox kt-checkbox--solid kt-checkbox--success kt-checkbox-override"><input type="checkbox" onclick="selectAllUploadingFile(this.checked)"><span></span></label></label></div>';
	setElmInnerHtmlByElmName('straight', colName);
	const routeName = isClient ? DASHBOARD_ROUTE : FILE_ROUTE;
	const batchFileUploadingBtns = getRouteVariable(routeName, 'batchFileUploadingBtns');
	updateElementStatus(batchFileUploadingBtns[3], enableUploadFile.length > 0);
	table.rows.add(data).draw();
	if (!isClient) {
		data.forEach((item, idx) => {
			const selectedFile = uploadingFiles.filter(item => `${item.idx}` === `${idx}`);
			if (selectedFile.length && !selectedFile[0].ignore) {
				onStraight(false, selectedFile[0].idx);
				setElmValueById(`straightFileName_${idx}`, selectedFile[0].name);
			}
		});
	}
	table
		.on('click', 'tbody tr a', function (e) {
			if (e.target.parentNode.name !== 'btnRemoveFile') return;
			try {
				const trElm = e.target.closest('tr');
				const currentRow = table.row(trElm);
				const removeIdx = table.row(trElm).index();
				const removingFile = uploadingFiles.filter(file => file.idx === removeIdx)[0];
				uploadingFiles.splice(uploadingFiles.indexOf(removingFile), 1);
				updateRouteVariable(COMMON, 'uploadingFiles', uploadingFiles);
				const uploadFileName = JSON.stringify(uploadingFiles);
				currentRow.remove();
				table.rows().draw();
				const enableUploadFile = uploadingFiles.filter(item => !item.ignore);
				updateElementStatus(batchFileUploadingBtns[3], enableUploadFile.length > 0);
				if (!enableUploadFile.length) {
					setElmInnerHtmlByElmName('straight', '');
					const elm = getElmById('filesContainer').elements[0];
					if (elm.value) {
						elm.value = '';
					}
				}
			} catch (err) {
				console.error(err);
			}
		});
}

/**
 * reload table data
 * @param table
 * @param data
 */
function reDrawTable(table, data) {
	if (table) {
		table.rows().remove();
		table.rows.add(data).draw();
		table.responsive.recalc();
		KTApp.initComponents();
	}
}

/**
 * update status for upload buttons
 * @param status
 */
function setUploadBtnStatus(status, isClient) {
	const routeName = isClient ? DASHBOARD_ROUTE : FILE_ROUTE;
	const batchFileUploadingBtns = getRouteVariable(routeName, 'batchFileUploadingBtns');
	setElementVisible(batchFileUploadingBtns[0], false);
	updateElementStatus(batchFileUploadingBtns[0], false);
	updateElementStatus(batchFileUploadingBtns[1], status);
	updateElementStatus(batchFileUploadingBtns[2], status);
	updateElementStatus(batchFileUploadingBtns[3], status);
	updateElementStatus('btnRemoveFile', status);
	updateElementStatus('btnDescription', status);
}

/**
 *
 * @param uploadingFiles
 */
function checkStraightSetting(uploadingFiles) {
	let result = true;
	uploadingFiles.forEach(item => {
		const straightElm = getElmById(`straightCbx_${item.idx}`);
		if (straightElm && straightElm.value === 'true') {
			const statusElm = getElmById(`straightStatus_${item.idx}`);
			const isSetup = JSON.parse(statusElm.getAttribute('isSetup'));
			if (!isSetup) {
				toastr.warning(`Setup Error on File: ${item.name.split('##')[1]}. Please select client to assign or uncheck the checkbox`);
				highlightButton(`straightSetting_${item.idx}`, true);
				result = false;
			}
		}
	});
	return result;
}

/**
 *
 * @returns {HTMLElement}
 */
function isStraightMode(index) {
	const cbx = getElmById(`straightCbx_${index}`);
	return cbx && cbx.value === 'true';
}

/**
 *
 * @param elm
 */
function uploadUrl(elm) {
	const listFiles = [];
	const form = $(elm).closest('form');
	let valid = true;
	const listUrl = [];
	for (let i = 0; i < form[0].elements.length; i++) {
		const inputElm = form[0].elements[i];
		if (inputElm.name === 'file_url') {
			if (inputElm.value.length === 0) {
				valid = false;
				inputElm.style.borderStyle = 'solid';
				inputElm.style.borderColor = 'red';
				inputElm.style.borderWidth = '1px';
				inputElm.setAttribute('placeholder', 'File url can not be blank');
			} else {
				if (inputElm.urlValid === false) {
					valid = false;
					continue;
				}
				inputElm.style.borderStyle = 'solid';
				inputElm.style.borderColor = 'lightgrey';
				inputElm.style.borderWidth = '1px';
				listUrl.push(inputElm.value);
			}
		}
	}
	if (!valid) {
		return;
	}
	closeModal('closeUploadUrlModal').then(async () => {
		for (let idx = 0; idx < listUrl.length; idx++) {
			const file = await readUrlToFile(listUrl[idx]);
			listFiles.push(file);
		}
		const routeName = elm.value === FILE_TYPE ? FILE_ROUTE : DASHBOARD_ROUTE;
		const listUploadingTable = getRouteVariable(routeName, 'listUploadingTable');
		await renderListFile(listUploadingTable, listFiles, elm.value !== FILE_TYPE);
	});
}

/**
 *
 * @param url
 * @returns {string}
 */
function getFileName(url) {
	let filename = url.substring(url.lastIndexOf('/') + 1);
	const paramsIdx = filename.includes('?') ? filename.indexOf('?') : 0;
	filename = filename.substring(0, paramsIdx === 0 ? filename.length : paramsIdx);
	return filename;
}

/**
 * reset form
 * @param elm
 */
function onCloseFormUrl(elm) {
	resetForm(elm);
	resetFileUrl();
}

/**
 *
 * @param url
 * @returns {Promise<File | never>}
 */
function readUrlToFile(url) {
	return fetch(url)
		.then(res => res.blob())
		.then(blob => {
			return new File([blob], getFileName(url), {type: "image/*"});
		});
}

/**
 * add more file url
 */
function addFileUrl() {
	const body = getElmById('fileUrlBody');
	const fileUrlGroup = createElm('div');
	body.appendChild(fileUrlGroup);
	fileUrlGroup.outerHTML = '<div style="display: flex; justify-content: flex-start; margin-top: 2px;">' +
		'<input class="form-control" name="file_url" placeholder="Input file url here" value="" oninput="validateStringUrl(this)"/>' +
		'<a href="javascript:;" onclick="addFileUrl()" data-toggle="kt-tooltip" title="Add more file" class="btn btn-secondary" style="padding: 2px 0 2px 2px; margin: 5px 1px 5px 5px;">' +
		'<i class="la la-plus"></i>' +
		'</a>' +
		'<a href="javascript:;" onclick="removeFileUrl(this)" class="btn btn-secondary" style="padding: 2px 0 2px 2px; margin: 5px 5px 5px 0;">' +
		'<i class="la la-minus"></i>' +
		'</a>' +
		'</div>';
	
}

/**
 * remove one file url
 */
function removeFileUrl(elm) {
	const childLength = getElmById('fileUrlBody').children.length;
	if (childLength > 1) {
		elm.parentNode.parentNode.removeChild(elm.parentNode);
	}
}

/**
 * reset body
 */
function resetFileUrl() {
	const body = getElmById('fileUrlBody');
	body.innerHTML = '';
	addFileUrl();
}

/**
 * validate input url
 * @param elm
 */
function validateStringUrl(elm) {
	const expression = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/g;
	const regex = new RegExp(expression);
	if (elm.value.match(regex)) {
		elm.style.borderStyle = 'solid';
		elm.style.borderColor = 'lightgrey';
		elm.style.borderWidth = '1px';
		elm.urlValid = true;
	} else {
		elm.style.borderStyle = 'solid';
		elm.style.borderColor = 'red';
		elm.style.borderWidth = '1px';
		elm.urlValid = false;
		elm.setAttribute('placeholder', 'File url is invalid');
	}
}

/**
 *
 * @param files: list of files
 * @param hasStraight: of upload template file only, hasStraight = false and otherwise
 * @param cb
 */
function doUploadToS3(hasStraight, cb) {
	setUploadBtnStatus(false, hasStraight);
	try {
		const uploadingFiles = getRouteVariable(COMMON, 'uploadingFiles');
		const allFiles = uploadingFiles.map(item => item.file);
		if (uploadingFiles.length === 0) {
			return;
		}
		const requestOption = {
			url: './new-file/file.php',
			method: 'POST'
		};
		if (!checkStraightSetting(uploadingFiles)) {
			setUploadBtnStatus(true, hasStraight);
			return;
		}
		
		const validFileToUpload = uploadingFiles.filter(item => !item.ignore);
		const form = buildMessageForm({
			filesToGetPreSignedUrl: JSON.stringify(validFileToUpload),
			action: 'getPreSignedUrl'
		});
		sendRequest(form, requestOption, function (response) {
			if (isSuccess(response)){
				let uploadSuccess = 0, numFileUploaded = response.data.length;
				response.data.map(preSignedUrlItem => {
					const {fileIdx, preSignedUrl, fileName, role} = preSignedUrlItem;
					const progressElm = getElmById(`uploadProgressBar_${fileIdx}`);
					const request = new XMLHttpRequest();
					request.open('PUT', preSignedUrl, true);
					request.setRequestHeader('Content-Disposition', 'attachment; filename=' + fileName + ''); // click on link to download file
					request.upload.onprogress = (e) => {
						request.fileIdx = fileIdx;
						request.fileName = fileName;
						const {lengthComputable, loaded, total} = e;
						if (lengthComputable) {
							let percentage = parseInt(loaded * 100 / total);
							progressElm.style.width = `${percentage}%`;
							progressElm.textContent = `${percentage}%`;
						}
					};
					request.onload = async () => {
						if (request.status === 200 && request.statusText === 'OK') {
							if (isStraightMode(request.fileIdx)) {
								const form = getElmByIdJQuery(`upload_${request.fileIdx}`);
								sendRequest(form, requestOption, (response) => {
									if (!isSuccess(response)) {
										cb(response);
									} else {
										uploadSuccess += 1;
										if (numFileUploaded === uploadSuccess) {
											updateRouteVariable(COMMON, 'uploadingFiles', [])
											cb(response);
										}
									}
								});
							} else {
								if (![ADMIN, CONSULTANT].includes(role)) {
									uploadSuccess += 1;
									if (numFileUploaded === uploadSuccess) {
										const project_id = getRouteVariable(DASHBOARD_ROUTE, 'project_id');
										const site_id = getRouteVariable(DASHBOARD_ROUTE, 'site_id');
										let container_id = getRouteVariable(DASHBOARD_ROUTE, 'container_id');
										let sub_container_id = getRouteVariable(DASHBOARD_ROUTE, 'sub_container_id');
										const formSync = buildMessageForm({
											file_name: JSON.stringify(uploadingFiles),
											project_id,
											site_id,
											client_id: getClientId(),
											container_id,
											sub_container_id,
											has_straight: true,
											action: 'syncClientData'
										});
										updateRouteVariable(COMMON, 'uploadingFiles', []);
										sendRequest(formSync, requestOption, cb);
									}
								} else {
									const fileToSync = uploadingFiles.filter(item => item.idx === request.fileIdx && item.name === request.fileName);
									const form = buildMessageForm({
										file_tpl_obj: JSON.stringify(fileToSync),
										action: 'syncFileTemplate'
									});
									sendRequest(form, requestOption, (response) => {
										if (!isSuccess(response)) {
											cb(response);
										} else {
											uploadSuccess += 1;
											if (numFileUploaded === uploadSuccess) {
												updateRouteVariable(COMMON, 'uploadingFiles', []);
												cb(response);
											}
										}
									});
								}
							}
						} else {
							toastr.error(`Uploaded file ${fileName} failed.`);
						}
					};
					request.send(allFiles[fileIdx]);
				});
			} else {
				toastr.error(response.msg);
			}
		});
	} catch (err) {
		toastr.error("Uploaded file failed");
		console.log(err);
		setUploadBtnStatus(true, hasStraight);
		updateRouteVariable(COMMON, 'uploadingFiles', [])
	}
}

/**
 * view image file on modal
 * @param fileUrl
 */
function viewImg(fileUrl, cb) {
	setElementVisibleObject({
		srcImg: true,
		srcWord: false,
		srcPdf: false,
		pdfSection: false,
		filledForm: false,
		copiedLink: true,
		downloadedLink: true,
		scaleFile: true,
		openPrintPage: false,
		exportPdfBtn: false
	});
	setElmTextByElmName('viewModalTitle', '');
	getElmById('srcImg').style.width = null;
	const img = new Image();
	img.onload = function () {
		const srcLink = getElmById('srcImg');
		srcLink.style.width = `${img.width * 0.5}px`;
		srcLink.setAttribute('mWidth', img.width);
		srcLink.setAttribute('src', fileUrl);
		setElmValueById('copiedLink', fileUrl);
		getElmById('downloadedLink').setAttribute('href', fileUrl);
		getElmById('scaleFile').setAttribute('fType', 'img');
		$('#scaleFile').val(0.5);
		cb();
	};
	img.src = fileUrl;
}

/**
 * view file | form on modal
 * @param fId
 * @param fName
 * @param templateId
 * @param type
 * @param viewMode
 */
function viewFileForm(fId, fName, templateId, type, viewMode) {
	if (type === FILE_TYPE) {
		const form = buildMessageForm({
			f_name: fName,
			action: 'getPreSignedUrl'
		});
		sendRequest(form, {
			url: './new-dashboard/dashboard.php',
			method: 'POST'
		}, (response) => {
			if (isSuccess(response)) {
				blockComponent('loadingIndicator');
				triggerButton('viewFileFormModal');
				if (isPdf(fName)) {
					viewPdf(response.data, () => {
						unblockComponent('loadingIndicator');
					});
				} else if (isImageFile(fName)) {
					viewImg(response.data, () => {
						unblockComponent('loadingIndicator');
					});
				} else if (isDocumentFile(fName)) {
					viewDocument(response.data, () => {
						unblockComponent('loadingIndicator');
					});
				}
			} else {
				toastr.error(response.msg);
			}
		});
	} else if (type === FORM_TYPE) {
		openForm(fId, fName, viewMode);
	} else if (type === FILLED_FORM) {
		blockComponent('loadingIndicator');
		viewFilledForm(fId, templateId, () => {
			unblockComponent('loadingIndicator');
		})
	}
}

/**
 * view doc, docx file
 * @param fileUrl
 * @param cb
 */
function viewDocument(fileUrl, cb) {
	setElementVisibleObject({
		srcImg: false,
		scaleFile: false,
		openPrintPage: false,
		srcWord: true,
		srcPdf: false,
		pdfSection: false,
		filledForm: false,
		copiedLink: true,
		downloadedLink: true,
		exportPdfBtn: false
	});
	getElmById('copiedLink').value = fileUrl;
	getElmById('downloadedLink').setAttribute('href', fileUrl);
	const srcWord = getElmById('srcWord');
	srcWord.setAttribute('src', `https://docs.google.com/gview?url=${encodeURIComponent(fileUrl)}&embedded=true`);
	cb();
}

/**
 * remove file | form action
 * @param fId
 * @param fType
 */
function removeFileForm(fId, fType) {
	formConfirm('Remove', 'Are you sure ?', null, null, (result) => {
		if (result) {
			const form = buildMessageForm({
				f_id: fId,
				f_type: fType,
				action: 'removeFilesFormsClient'
			});
			sendRequest(form, {
				url: './new-dashboard/dashboard.php',
				method: 'POST'
			}, (response) => {
				if (isSuccess(response)) {
					updateTableFileFormContainer();
					toastr.success(response.msg);
				} else {
					toastr.error(response.msg);
				}
			})
		}
	});
}

/**
 * view pdf file on modal
 * @param fileUrl
 */
function viewPdf(fileUrl, cb) {
	setElementVisibleObject({
		srcImg: false,
		srcWord: false,
		srcPdf: true,
		pdfSection: true,
		filledForm: false,
		copiedLink: true,
		downloadedLink: true,
		scaleFile: true,
		openPrintPage: true,
		exportPdfBtn: false
	});
	setElmTextByElmName('viewModalTitle', '');
	getElmById('srcPdf').style.width = null;
	getElmById('copiedLink').value = fileUrl;
	getElmById('downloadedLink').setAttribute('href', fileUrl);
	const loadingTask = pdfjsLib.getDocument(fileUrl);
	loadingTask.promise.then(function (pdf) {
		document.pdfInstance = pdf;
		setElmValuePropByElmName('pdfCurrentPage', 1);
		setElmValuePropByElmName('pdfNumPages', pdf.numPages);
		setElmTextByElmName('pdfNumPages', pdf.numPages);
		goToPage(1);
		cb();
	}, function (reason) {
		cb(reason);
	});
}

/**
 * print page
 * @returns {Promise<void>}
 */
async function renderPdfPrinting() {
	const printWin = window.open('', '', 'height=700, width=900');
	const page = await document.pdfInstance.getPage(1);
	await handlePages(printWin, page, 1);
}

/**
 *
 * @param printWin
 * @param page
 * @param currentPageIdx
 * @returns {Promise<void>}
 */
async function handlePages(printWin, page, currentPageIdx) {
	const scale = 1.5;
	const viewport = page.getViewport({scale: scale});
	
	const canvas = document.createElement("canvas");
	canvas.setAttribute('class', 'border-bottom');
	const context = canvas.getContext('2d');
	canvas.height = viewport.height;
	canvas.width = viewport.width;
	
	await page.render({canvasContext: context, viewport: viewport});
	printWin.document.body.appendChild(canvas);
	
	currentPageIdx++;
	if (document.pdfInstance !== null && currentPageIdx <= document.pdfInstance.numPages) {
		const page = await document.pdfInstance.getPage(currentPageIdx);
		await handlePages(printWin, page, currentPageIdx);
	} else {
		printWin.print();
		printWin.close();
	}
}

/**
 *
 * @param oldCanvas
 * @returns {HTMLElement}
 */
function cloneCanvas(printWin, oldCanvas) {
	const newCanvas = printWin.document.createElement('canvas');
	const context = newCanvas.getContext('2d');
	newCanvas.width = oldCanvas.width;
	newCanvas.height = oldCanvas.height;
	context.drawImage(oldCanvas, 0, 0, oldCanvas.width, oldCanvas.height);
	return newCanvas;
}

/**
 * view pdf file page
 * @param pageNumber
 */
function viewPdfPage(pageNumber) {
	document.pdfInstance.getPage(pageNumber).then(function (page) {
		const scale = 1.5;
		const viewport = page.getViewport({scale: scale});
		
		// Prepare canvas using PDF page dimensions
		const canvas = getElmById('srcPdf');
		const context = canvas.getContext('2d');
		
		canvas.width = viewport.width;
		canvas.height = viewport.height;
		canvas.setAttribute('mWidth', viewport.width);
		
		// Render PDF page into canvas context
		const renderContext = {
			canvasContext: context,
			viewport: viewport
		};
		page.render(renderContext).then(function () {
			getElmById('scaleFile').setAttribute('fType', 'pdf');
			$('#scaleFile').val(1);
		});
	});
}

/**
 * view filled form on modal
 * @param dataId
 * @param templateId
 */
function viewFilledForm(dataId, templateId, cb) {
	setElementVisibleObject({
		srcImg: false,
		srcWord: false,
		srcPdf: false,
		pdfSection: false,
		filledForm: true,
		copiedLink: false,
		downloadedLink: false,
		scaleFile: false,
		openPrintPage: false,
		exportPdfBtn: true
	});
	const form = buildMessageForm({
		dataId,
		templateId,
		action: 'getFormDataJson'
	});
	triggerButton('viewFileFormModal');
	sendRequest(form, {
		url: './new-form-viewer/form.viewer.php',
		method: 'GET'
	}, function (response) {
		if (isSuccess(response)) {
			const dataObject = response.data;
			setElmTextByElmName('viewModalTitle', `Form: ${dataObject.data.name}`);
			const survey = new Survey.Model(dataObject.template.json);
			survey.data = JSON.parse(dataObject.data.json);
			survey.mode = 'display';
			const formData = {
				json: JSON.parse(dataObject.template.json),
				data: JSON.parse(dataObject.data.json),
				name: dataObject.data.name
			};
			updateRouteVariable(COMMON, 'currentFormData', formData);
			$('#filledForm').Survey({
				model: survey,
				onComplete: (survey) => {
				}
			});
			const completedBtn = [...document.getElementsByClassName('sv_complete_btn')].filter(btn => !btn.id);
			completedBtn[0].id = 'filledFormModal';
			completedBtn[0].style.display = 'none';
		} else {
			toastr.error(response.msg);
		}
		cb();
	});
}

/**
 * export pdf file
 */
function exportPDF() {
	const formData = getRouteVariable(COMMON, 'currentFormData');
	const surveyPDF = new SurveyPDF.SurveyPDF(formData.json, {commercial: true});
	surveyPDF.data = formData.data;
	surveyPDF.mode = "display";
	surveyPDF.save(formData.name);
}

/**
 * open form on modal
 * @param formId
 * @param formName
 * @param viewMode
 */
function openForm(formId, formName, viewMode) {
	setElmValuePropByElmName('client_id', getClientId());
	setElmTextByElmName('loadFormName', formName);
	setElementVisibleObject({
		exportPdfBtn: false,
		save_form_group: !viewMode,
		save_filled_form_btn: !viewMode
	});
	triggerButton('loadFileFormModal');
	const form = buildMessageForm({
		surveyId: formId,
		mode: INSIDE_CLIENT,
		action: 'getSurveyJson'
	});
	sendRequest(form, {
		url: './new-form-viewer/form.viewer.php',
		method: 'GET'
	}, function (response) {
		if (isSuccess(response)) {
			const formData = response.data.template;
			const isManual = !['pending', 'auto'].includes(formData.save_mode);
			setElementVisible('save_form_group', isManual);
			const project_id = getRouteVariable(DASHBOARD_ROUTE, 'project_id');
			const site_id = getRouteVariable(DASHBOARD_ROUTE, 'site_id');
			let container_id = getRouteVariable(DASHBOARD_ROUTE, 'container_id');
			let sub_container_id = getRouteVariable(DASHBOARD_ROUTE, 'sub_container_id');
			if (!isManual) {
				const defaultLocation = formData.default_location.split(':');
				container_id = defaultLocation[0];
				sub_container_id = defaultLocation[1];
			}
			const survey = new Survey.Model(formData.json);
			getElmByIdJQuery('mainFormModal').Survey({
				model: survey,
				onComplete: (survey) => {
					const form = buildMessageForm({
						client_id: getClientId(),
						form_name: getElmValueById('filled_form_name'),
						form_template_id: formData.id,
						form_data: JSON.stringify(survey.data, undefined, 2),
						save_mode: formData.save_mode,
						project_id,
						site_id,
						container_id,
						sub_container_id,
						action: 'saveFilledForm'
					});
					sendRequest(form, {
						url: './new-form-viewer/form.viewer.php',
						method: 'POST'
					}, (response) => {
						if (isSuccess(response)) {
							toastr.success(response.msg);
							triggerButton('close_filled_form_btn');
						} else {
							toastr.error(response.msg);
						}
					})
				}
			});
			const completedBtn = [...document.getElementsByClassName('sv_complete_btn')].filter(btn => !btn.id);
			completedBtn[0].id = 'mainFormModal';
			completedBtn[0].style.display = 'none';
		} else {
			toastr.error(response.msg);
		}
	});
}

/**
 * close form
 */
function closeForm() {
	updateElementStatus('filled_form_name', true);
	setElmValueById('filled_form_name', '');
	getElmByIdJQuery('container_location').val(-1);
	getElmByIdJQuery('sub_container_location').val(-1);
	updateTableFileFormContainer();
}

/**
 * scale image | pdf
 * @param type
 * @param size
 */
function scale(type, size) {
	if (type === 'img') {
		const imgElm = getElmById('srcImg');
		imgElm.style.width = `${imgElm.getAttribute('mWidth') * size}px`;
	} else if (type === 'pdf') {
		const pdfElm = getElmById('srcPdf');
		pdfElm.style.width = `${pdfElm.getAttribute('mWidth') * size}px`;
	}
}

/**
 * get current page in modal
 * @returns {*}
 */
function getCurrentPage() {
	return parseInt(document.getElementsByName('pdfCurrentPage')[0].value);
}

/**
 * get number of pages
 * @returns {number}
 */
function getNumPages() {
	return parseInt(document.getElementsByName('pdfNumPages')[0].value);
}

/**
 * go to page by page number
 * @param event
 */
function goToPage(pageNumber) {
	if (pageNumber < 1 || pageNumber > getNumPages()) {
		toastr.error('Page number is invalid');
		updateElementStatus('nextPageBtn', false)
		updateElementStatus('previousPageBtn', false)
		return;
	}
	
	updateElementStatus('nextPageBtn', pageNumber === getNumPages() ? false : true);
	updateElementStatus('previousPageBtn', pageNumber === 1 ? false : true);
	
	setElmValuePropByElmName('pdfCurrentPage', pageNumber);
	viewPdfPage(pageNumber);
}

/**
 * go next page
 */
function goNextPage() {
	goToPage(getCurrentPage() + 1);
}

/**
 * go previous page
 */
function goPreviousPage() {
	goToPage(getCurrentPage() - 1);
}

/**
 *
 * @param value
 */
function copyClipboard(value) {
	navigator.clipboard.writeText(value);
	toastr.success('Copied');
}

/**
 * convert node list element to array JS
 * @param nodeList
 */
function toArray(nodeList) {
	return nodeList ? [...nodeList] : [];
}

/**
 *
 * @param response
 * @returns {boolean}
 */
function isSuccess(response) {
	return 'ok' === response.result;
}

/**
 * clear form data on close
 */
function onFileFormModalClose() {
	//clear pdf section
	const canvas = getElmById('srcPdf');
	const context = canvas.getContext('2d');
	context.clearRect(0, 0, canvas.width, canvas.height);
	
	//clear image section
	const srcImg = getElmById('srcImg');
	srcImg.removeAttribute('src');
	
	//clear word section
	const srcWord = getElmById('srcWord');
	srcWord.removeAttribute('src');
}

/**
 *
 * @param user
 * @returns {string}
 */
function getFullName(user) {
	const {username, first_name, last_name} = user;
	return (first_name !== '' || last_name !== '') ? `${first_name} ${last_name.charAt(0)}.` : username;
}

/**
 *
 * @param elmId
 * @param time
 * @param mode
 * @returns {Promise}
 */
function asyncFade(elmId, time, mode) {
	const elm = getElmByIdJQuery(elmId);
	return new Promise(resolve => {
		if (mode === 'fadeIn') {
			elm.fadeIn(time, () => {
				resolve();
			});
		} else if (mode === 'fadeOut') {
			elm.fadeOut(time, () => {
				resolve();
			});
		}
	});
}

/**
 *
 * @param userId
 */
function loginAs(userId) {
	const form = buildMessageForm({
		userId,
		action: 'loginAsUser'
	});
	sendRequest(form, {
		url: './new-user/user.php',
		method: 'POST'
	}, (response) => {
		if (isSuccess(response)) {
			location.reload();
		} else {
			toastr.error(response.msg);
		}
	})
}

/**
 *
 * @param batchBtns
 * @param status
 */
function resetBatchUploadedAction(batchBtns, status) {
	if (batchBtns && batchBtns.length > 1) {
		updateElementStatus(batchBtns[0], status);
		updateElementStatus(batchBtns[1], status);
		const currentUser = getRouteVariable(COMMON, 'currentUser');
		setElementVisible(batchBtns[1], [ADMIN].includes(currentUser && currentUser.role));
	}
}

/**
 * Fast UUID generator, RFC4122 version 4 compliant.
 * @author Jeff Ward (jcward.com).
 * @license MIT license
 * @link http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript/21963136#21963136
 **/
const UUID = (function () {
	const self = {};
	const lut = [];
	for (let i = 0; i < 256; i++) {
		lut[i] = (i < 16 ? '0' : '') + (i).toString(16);
	}
	self.generate = function () {
		const d0 = Math.random() * 0xffffffff | 0;
		const d1 = Math.random() * 0xffffffff | 0;
		const d2 = Math.random() * 0xffffffff | 0;
		const d3 = Math.random() * 0xffffffff | 0;
		return lut[d0 & 0xff] + lut[d0 >> 8 & 0xff] + lut[d0 >> 16 & 0xff] + lut[d0 >> 24 & 0xff] + '-' +
			lut[d1 & 0xff] + lut[d1 >> 8 & 0xff] + '-' + lut[d1 >> 16 & 0x0f | 0x40] + lut[d1 >> 24 & 0xff] + '-' +
			lut[d2 & 0x3f | 0x80] + lut[d2 >> 8 & 0xff] + '-' + lut[d2 >> 16 & 0xff] + lut[d2 >> 24 & 0xff] +
			lut[d3 & 0xff] + lut[d3 >> 8 & 0xff] + lut[d3 >> 16 & 0xff] + lut[d3 >> 24 & 0xff];
	}
	return self;
})();

/**
 *
 * @param title
 * @param isUser
 */
function triggerPanel(title, isUser) {
	getElmById('kt-panel-head').innerText = title;
	setElementVisible('m_add_client_section', !isUser);
	setElementVisible('m_add_user_section', isUser);
	triggerButton('kt_demo_panel_toggle');
}

/**
 *
 * @param elmId
 * @returns {*|jQuery}
 */
function isElmVisible(elmId) {
	return getElmByIdJQuery(elmId).is(':visible')
}

/**
 *
 * @param fileIdx
 */
function showDescriptionModal(fileIdx) {
	getElmById('fileDescArea').setAttribute('file-idx', fileIdx);
	triggerButton('addFileDescription');
}

/**
 * init tooltip for uploading button
 */
function initTooltipUploadBtn() {
	const descElms = getElmsByNameJQuery('a', 'btnDescription');
	const removeElms = getElmsByNameJQuery('a', 'btnRemoveFile');
	if (descElms.length > 1) {
		KTApp.initTooltip(descElms);
	}
	if (removeElms.length > 1) {
		KTApp.initTooltip(removeElms);
	}
}

/**
 * update file description
 */
function updateFileDescription(elm) {
	const form = $(elm).closest('form');
	form.validate({
		rules: {
			fileDescArea: {
				required: true,
			}
		}
	});
	if (!form.valid()) {
		return;
	}
	const elmDesc = getElmById('fileDescArea');
	const idx = elmDesc.getAttribute('file-idx');
	const uploadingFiles = getRouteVariable(COMMON, 'uploadingFiles');
	for (let i = 0; i < uploadingFiles.length; i++) {
		if (`${uploadingFiles[i].idx}` === idx) {
			setElmTextByElmName(`fileDesc_${idx}`, elmDesc.value);
			uploadingFiles[i]['description'] = elmDesc.value;
		}
	}
	closeModal('closeFileDescription').then(() => {
		resetForm(form);
	});
}

/**
 *
 * @param strs
 * @returns {findMatches}
 */
function substringMatcher(strs) {
	return function findMatches(q, cb) {
		let matches = [];
		let substrRegex = new RegExp(q, 'i');
		strs.forEach((str) => {
			if (substrRegex.test(str)) {
				matches.push(str);
			}
		});
		cb(matches);
	};
}

/**
 *
 * @param str
 * @param length
 * @returns {string}
 */
function truncate(str, length) {
	if (str.length <= length) return str;
	return `${str.substr(0, length)}...`
}

/**
 *
 * @param fieldNames
 * @returns {any}
 */
function buildMessageForm(fieldNames) {
	const form = createElm('form');
	for (let fName in fieldNames) {
		const field = createElm('input');
		field.setAttribute('name', fName);
		field.setAttribute('value', fieldNames[fName]);
		form.appendChild(field);
	}
	return $(form);
}

/**
 * preview data form
 * @param elmId
 * @param formData
 * @param isFilledForm
 * @param readOnly
 * @param cb
 */
function loadViewer(elmId, formData, isFilledForm, readOnly, cb = () => {
}) {
	Survey
		.StylesManager
		.applyTheme("darkblue");
	const survey = new Survey.Model(formData.template.json);
	if (isFilledForm) {
		survey.data = JSON.parse(formData.data.json);
	}
	survey.name = formData.template.name;
	if (readOnly) {
		survey.mode = 'display';
	}
	getElmByIdJQuery(elmId).Survey({
		model: survey,
		onComplete: cb
	});
	const btnSaveForm = getElmByClassName('sv_complete_btn');
	btnSaveForm.style.display = 'none';
}