<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-06-02
	 * Time: 15:34
	 */
	
	function render()
	{
		session_start();
		require_once './services/UserService.php';
		require_once './services/EnvService.php';
		if (UserService::getInstance()->getCurrentUser()->isAuthenticated()) {
			header('Location: ./manage');
		} else {
			$appVersion = EnvService::getInstance()->getEnvByKey('APP_VERSION');
			if (!$appVersion) {
				$appVersion = '1.0.0';
			}
			require_once "auth.view.php";
		}
	}
	
	if (isset($_POST['loginSubmit'])) {
		require_once '../assets/vendors/custom/moment/vendor/autoload.php';
		require_once '../consts.php';
		require_once '../services/UserService.php';
		require_once 'login/login.inc.php';
		\Moment\Moment::setDefaultTimezone('UTC');
		
		$email = $_POST['email'];
		$password = $_POST['password'];
		if (empty($email) || empty($password)) {
			print_r(json_encode(array('status' => CODE_UNAUTHORIZED, 'msg' => 'Unauthorized')));
			exit();
		}
		$authLogin = new AuthLogin();
		$remember = isset($_POST['remember']) && $_POST['remember'] === 'on' ? true : false;
		$authLogin->login($email, $password, $remember);
	} else if (isset($_POST['signUpSubmit'])) {
		require_once 'signup/signup.inc.php';
		$authSignUp = new AuthSignUp();
		$authSignUp->signUp($_POST['username'], $_POST['email'], $_POST['password'], $_POST['rpassword']);
	}  else {
		render();
	}