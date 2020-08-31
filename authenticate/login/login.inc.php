<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-06-02
	 * Time: 21:53
	 */
	require_once '../services/DatabaseService.php';
	require_once '../services/UserService.php';
	require_once '../consts.php';
	
	class AuthLogin
	{
		function login($email, $password, $remember)
		{
			$conn = DatabaseService::getInstance()->connect();
			$sql = 'SELECT * FROM users WHERE email = ? AND deleted = ? AND role IN ("admin", "user")';
			$stmt = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				print_r(json_encode(array('status' => CODE_SERVER_ERROR, 'msg' => 'Server error')));
				exit();
			} else {
				$deleted = 0;
				mysqli_stmt_bind_param($stmt, 'ss', $email, $deleted);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				if ($row = mysqli_fetch_assoc($result)) {
					$passwordCheck = password_verify($password, $row['password']);
					if ($passwordCheck === false) {
						print_r(json_encode(array('status' => CODE_UNAUTHORIZED, 'msg' => 'Incorrect username or password. Please try again.')));
						exit();
					} else if ($passwordCheck === true) {
						session_start();
						$currentUser = UserService::getInstance()->getCurrentUser();
						$currentUser->setUser($row, $remember);
						print_r(json_encode(array('status' => CODE_SUCCESS, 'msg' => 'Login success')));
						exit();
					} else {
						print_r(json_encode(array('status' => CODE_UNAUTHORIZED, 'msg' => 'Incorrect username or password. Please try again.')));
						exit();
					}
				} else {
					print_r(json_encode(array('status' => CODE_SERVER_ERROR, 'msg' => 'Incorrect username or password. Please try again.')));
					exit();
				}
			}
			mysqli_stmt_close($stmt);
			mysqli_close($conn);
		}
	}
	