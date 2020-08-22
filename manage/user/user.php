<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-06-03
	 * Time: 23:17
	 */
	session_start();
	
	require_once '../utils/utils.php';
	require_once '../permission/user-permission.php';
	require_once '../../consts.php';
	require_once '../../services/S3Service.php';
	require_once '../../services/UserService.php';
	require_once '../../services/ClientService.php';
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
		$users = UserService::getInstance()->getAllUsers(false);
		require_once 'user.view.php';
	}
	
	/**
	 * @return string
	 */
	function genRandomPassword()
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < 10; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}
	
	/**
	 * handle client request
	 */
	if (verifyAuth()) {
		$currentUser = UserService::getInstance()->getCurrentUser();
		$action = getActionName();
		if ($action === 'renderPage') {
			render();
		} else if ($action === 'addUser') {
			if (!canAddUser($currentUser)) {
				exitOnNoPermission();
			}
			$email = $_POST['email'];
			$username = isset($_POST['username']) ? $_POST['username'] : '';
			$userExisted = UserService::getInstance()->getUserByInfo($email);
			if ($userExisted->num_rows > 0) {
				print_r(json_encode(array('result' => 'taken', 'msg' => 'User is taken')));
				exit();
			}
			$deleted = (isset($_POST['active_on_create']) && $_POST['active_on_create'] === 'on') ? 0 : 1;
			$password = (isset($_POST['password']) && strlen($_POST['password']) > 0) ? $_POST['password'] : genRandomPassword();
			$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
			$arrayData = array('email' => $email, 'username' => $username, 'password' => $hashedPwd, 'createdDate' => gmdate(TIME_FORMAT), 'role' => $_POST['role'], 'first_name' => $_POST['firstName'], 'last_name' => $_POST['lastName'], 'deleted' => $deleted, 'term' => 0, 'user_client' => (isset($_POST['user_client']) && $_POST['user_client'] !== '-1') ? $_POST['user_client'] : '');
			UserService::getInstance()->addUser($arrayData);
			if ($deleted === 0) {
				MailerService::getInstance()->sendAddedUserInfo($email, array('user_password' => $password));
			} else {
				MailerService::getInstance()->sendVerificationEmail($email, $password, $hashedPwd);
			}
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'updateUser') {
			if (!canUpdateUser($currentUser)) {
				exitOnNoPermission();
			}
			$user_id = $_POST['user_id'];
			$user = UserService::getInstance()->getUserById($user_id);
			if ($user) {
				if (((string)$user['id'] !== (string)$_POST['user_id'] && (string)$user['email'] === (string)$_POST['user_email'])) {
					print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'User is taken')));
					exit();
				}
			}
			$arrayData = array('id' => $_POST['user_id'], 'title' => $_POST['title'], 'first_name' => $_POST['first_name'], 'last_name' => $_POST['last_name'], 'role' => $_POST['role'], 'deleted' => $_POST['deleted'], 'address' => $_POST['address'], 'address2' => $_POST['address2'], 'city' => $_POST['city'], 'province' => $_POST['province'], 'postal_code' => $_POST['postal_code'], 'country' => $_POST['country'], 'company' => $_POST['company'], 'phone' => $_POST['phone']);
			UserService::getInstance()->updateUser($arrayData);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'removeUser') {
			if (!canRemoveUser($currentUser)) {
				exitOnNoPermission();
			}
			$fId = $_POST['user_id'];
			UserService::getInstance()->deleteUserById($fId);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'getAssociatedClients') {
			if (!canGetAssociatedClient($currentUser)) {
				exitOnNoPermission();
			}
			$userId = $_POST['userId'];
			$userClient = UserService::getInstance()->getClientByUserId($userId);
			if (strlen($userClient['client_id']) === 0) {
				print_r(json_encode(array('result' => RESULT_OK, 'msg' => 'Get associated client successful', 'data' => array())));
				exit();
			}
			$client = ClientService::getInstance()->getClientById($userClient['client_id']);
			sendResponse(RESULT_OK, MSG_SUCCESS, $client ? [$client] : []);
		} else if ($action === 'assignUserToClient') {
			if (!canAssignUser($currentUser)) {
				exitOnNoPermission();
			}
			$userId = $_POST['userId'];
			$clientId = decode64($_POST['clientId']);
			$result = UserService::getInstance()->assignUserToClient($clientId, $userId);
			sendResponse(RESULT_OK, MSG_SUCCESS, $result);
		} else if ($action === 'loginAsUser') {
			if (!canLoginAs($currentUser)) {
				exitOnNoPermission();
			}
			$user = UserService::getInstance()->getUserById($_POST['userId']);
			if ($user['role'] === SOFT_USER) {
				print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'This user is not allow to login')));
				exit();
			}
			if ($user['client_id'] === '' && in_array($user['role'], array(USER_NORMAL, USER_MANAGER))) {
				print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'This user is not assigned to any client. Cannot perform this action.')));
				exit();
			}
			$currentUser->setNewUserSession($user);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'exitLoginAsUser') {
			if (!canExitLoginAsUser($currentUser)) {
				exitOnNoPermission();
			}
			$currentUser->restoreLastSession();
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'handleUserTerm') {
			if (!canAgreeTerm($currentUser)) {
				exitOnNoPermission();
			}
			$agree = $_POST['user_agree'] === '1' ? true : false;
			if ($agree) {
				UserService::getInstance()->updateUserTerm($currentUser->getId());
				$currentUser->setTerm(1);
				sendResponse(RESULT_OK, MSG_SUCCESS);
			} else {
				session_unset();
				session_destroy();
				sendResponse(RESULT_FAIL, MSG_FAILED);
			}
		} else if ($action === 'getUserNotification') {
			if (!canHandleUserNotification($currentUser)) {
				exitOnNoPermission();
			}
			$result = NotificationService::getInstance()->getUserNotification();
			print_r(json_encode(array('result' => RESULT_OK, 'msg' => 'Success', 'data' => toArray($result))));
		} else if ($action === 'setReadNotification') {
			if (!canHandleUserNotification($currentUser)) {
				exitOnNoPermission();
			}
			$data = array('notification_id' => $_POST['notification_id'], 'notification_data' => $_POST['notification_data']);
			NotificationService::getInstance()->setReadNotification($data);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'getAllUsers') {
			if (!canGetAllUsers($currentUser)) {
				exitOnNoPermission();
			}
			$usersReturn = array();
			$loadSoftUser = $_GET['load_soft_user'];
			$users = $loadSoftUser === 'false' ? UserService::getInstance()->getAllUsers(false) : UserService::getInstance()->getAllUsers(true);
			foreach ($users as $user) {
				$user['current_user_role'] = $currentUser->getRole();
				$userAvatar = $user['avatar'];
				if (strlen($user['avatar']) === 0) {
					$userAvatar = 'profile_avatar/un_set.png';
				}
				$avatarUrl = S3Service::getInstance()->getPreSignedUrl($userAvatar, 'getObject');
				$user['avatar'] = $avatarUrl;
				unset($user['password'], $user['session_id']);
				array_push($usersReturn, $user);
			}
			sendResponse(RESULT_OK, MSG_SUCCESS, $usersReturn);
		} else if ($action === 'getUser') {
			if (!canGetUser($currentUser)) {
				exitOnNoPermission();
			}
			$user = UserService::getInstance()->getUserById($_GET['user_id']);
			$fileName = $user['avatar'];
			if (strlen($user['avatar']) === 0) {
				$fileName = 'profile_avatar/un_set.png';
			}
			$avatarUrl = S3Service::getInstance()->getPreSignedUrl($fileName, 'getObject');
			$user['avatar'] = $avatarUrl;
			unset($user['password'], $user['session_id']);
			sendResponse(RESULT_OK, MSG_SUCCESS, $user);
		} else if ($action === 'getCurrentUser') {
			if (!canGetUser($currentUser)) {
				exitOnNoPermission();
			}
			sendResponse(RESULT_OK, MSG_SUCCESS, ['id' => $currentUser->getId(), 'fullName' => $currentUser->getDisplayName(), 'email' => $currentUser->getEmail(), 'role' => $currentUser->getRole(), 'term' => $currentUser->getTerm(), 'clientId' => $currentUser->getClientId(), 'viewMode' => $currentUser->isViewMode()]);
		} else {
			exitOnNoPermission();
		}
	} else {
		accessDenied();
	}