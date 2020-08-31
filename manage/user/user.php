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
	 * render current page
	 */
	function render()
	{
		$currentUser = UserService::getInstance()->getCurrentUser();
		require_once 'user.view.php';
	}
	
	/**
	 * handle client request
	 */
	if (verifyAuth()) {
		$currentUser = UserService::getInstance()->getCurrentUser();
		$action = getActionName();
		if ($action === 'renderPage') {
			render();
		} else if ($action === 'getCurrentUser') {
			sendResponse(RESULT_OK, MSG_SUCCESS, ['id' => $currentUser->getId(), 'fullName' => $currentUser->getDisplayName(), 'email' => $currentUser->getEmail(), 'role' => $currentUser->getRole()]);
		} else if ($action === 'getAllUsers') {
			$usersReturn = array();
			$users = UserService::getInstance()->getAllUsers();
			foreach ($users as $user) {
				$user['current_user_role'] = $currentUser->getRole();
				unset($user['password'], $user['session_id']);
				array_push($usersReturn, $user);
			}
			sendResponse(RESULT_OK, MSG_SUCCESS, $usersReturn);
		} else if ($action === 'updateUser') {
			$user_id = $_POST['user_id'];
			$user = UserService::getInstance()->getUserById($user_id);
			if ($user) {
				if (((string)$user['id'] !== (string)$_POST['user_id'] && (string)$user['email'] === (string)$_POST['user_email'])) {
					print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'User is taken')));
					exit();
				}
			}
			$arrayData = array('id' => $_POST['user_id'], 'title' => $_POST['title'], 'first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name'], 'role' => $_POST['role'], 'deleted' => $_POST['deleted'], 'address' => $_POST['address'], 'city' => $_POST['city'], 'province' => $_POST['province'], 'country' => $_POST['country'], 'company' => $_POST['company'], 'phone' => $_POST['phone']);
			UserService::getInstance()->updateUser($arrayData);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'removeUser') {
			$fId = $_POST['user_id'];
			UserService::getInstance()->deleteUserById($fId);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		}
	} else {
		accessDenied();
	}