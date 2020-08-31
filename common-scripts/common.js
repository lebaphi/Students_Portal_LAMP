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
 *
 * @param elmId
 * @returns {jQuery|HTMLElement}
 */
function getElmByIdJQuery(elmId) {
	return $(`#${elmId}`);
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
 * click the button by button id
 * @param buttonId
 */
function triggerButton(buttonId) {
	getElmById(buttonId).click();
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
 * prepare to load page
 */
function prepareToLoad() {
	const openingTooltips = document.getElementsByClassName('tooltip fade');
	for (const openingTooltip of openingTooltips) {
		openingTooltip.parentNode.removeChild(openingTooltip);
	}
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
 * create element
 * @param tagName
 * @returns {any}
 */
function createElm(tagName) {
	return document.createElement(tagName);
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