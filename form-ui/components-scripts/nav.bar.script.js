/**
 * exit user session
 */
function exitUserSession() {
	const form = buildMessageForm({
		action: 'exitLoginAsUser'
	});
	sendRequest(form, {
		url: './new-user/user.php',
		method: 'POST'
	}, (response) => {
		if (isSuccess(response)) {
			localStorage.setItem('defaultPage', './new-user/user.php');
			location.reload();
		} else {
			toastr.error(response.msg);
		}
	})
}

/**
 * handle signout
 * @param elm
 */
function handleSignOut(elm) {
	if (elm) {
		elm.setAttribute('type', 'submit');
		elm.click();
	}
}

/**
 *
 * @param userId
 */
function loadNotification(userId) {
	const form = buildMessageForm({
		action: 'getUserNotification'
	});
	sendRequest(form, {
		url: './new-user/user.php',
		method: 'GET'
	}, (response) => {
		if (isSuccess(response)) {
			const notificationGroup = getElmById('notification_group');
			notificationGroup.innerHTML = '';
			const newNotifications = response.data.filter(item => {
				const {id, is_new, msg, created_date} = item;
				const isNew = JSON.parse(is_new);
				const isNotRead = isNew.filter(item => item === userId).length === 0;
				const data = JSON.stringify([...isNew, userId]);
				const notificationElm = createElm('div');
				notificationGroup.appendChild(notificationElm);
				const newClassIcon = isNotRead ? 'flaticon2-notification kt-font-success' : 'flaticon2-notification kt-font-dark';
				const newClassMsg = isNotRead ? 'kt-notification__item-title kt-font-success kt-font-boldest' : 'kt-notification__item-title';
				notificationElm.outerHTML = '<div class="kt-notification__item">' +
						'<div class="kt-notification__item-icon">' +
						'<i class="' + newClassIcon + '" id="notification_icon_' + id + '"></i></div>' +
						'<div class="kt-notification__item-details" onclick="setOld(' + id + ', ' + isNotRead + ',' + userId + ',' + data + ')">' +
						'<div class="' + newClassMsg + '" id="notification_msg_' + id + '">' + msg + '</div>' +
						'<div class="kt-notification__item-time">' + moment.utc(created_date).local().fromNow() + '</div>' +
						'</div>' +
						'</div>';
				
				if (isNotRead) {
					return item;
				}
			});
			
			// notificationGroup.innerHTML = '<div class="kt-grid kt-grid--ver" style="min-height: 150px;"><div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">' +
			// 	'<div class="kt-grid__item kt-grid__item--middle kt-align-center">' +
			// 	'All caught up!' +
			// 	'<br>No new notifications.' +
			// 	'</div></div></div>';
			
			setElementVisible('notification_count', newNotifications.length > 0);
			getElmById('notification_count').innerText = `${newNotifications.length} New`;
		} else {
			toastr.error(response.msg);
		}
	})
}

/**
 *
 * @param elmId
 * @param isNew
 * @param userId
 * @param data
 */
function setOld(elmId, isNew, userId, data) {
	if (!isNew) return;
	const iconElm = getElmById(`notification_icon_${elmId}`);
	const msgElm = getElmById(`notification_msg_${elmId}`);
	iconElm.setAttribute('class', 'flaticon2-notification kt-font-dark');
	msgElm.setAttribute('class', 'kt-notification__item-title');
	const form = buildMessageForm({
		notification_id: elmId,
		notification_data: JSON.stringify(data),
		action: 'setReadNotification'
	});
	sendRequest(form, {
		url: './new-user/user.php',
		method: 'POST'
	}, (response) => {
		if (isSuccess(response)) {
			loadNotification(userId);
		} else {
			toastr.error(response.msg);
		}
	})
}