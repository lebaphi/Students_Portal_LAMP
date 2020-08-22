<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-06-03
	 * Time: 23:17
	 */
	session_start();
	
	require_once '../utils/utils.php';
	require_once '../../consts.php';
	require_once '../../services/UserService.php';
	require_once '../../services/NotificationService.php';
	require_once '../../services/MailerService.php';
	require_once '../../assets/vendors/custom/moment/vendor/autoload.php';
	
	\Moment\Moment::setDefaultTimezone('UTC');
	
	/**
	 * handle client request
	 */
	if (verifyAuth()) {
		$currentUser = UserService::getInstance()->getCurrentUser();
		$action = getActionName();
		if ($action === 'getCurrentUser') {
			sendResponse(RESULT_OK, MSG_SUCCESS, ['id' => $currentUser->getId(), 'fullName' => $currentUser->getDisplayName(), 'email' => $currentUser->getEmail(), 'role' => $currentUser->getRole()]);
		}
	} else {
		accessDenied();
	}