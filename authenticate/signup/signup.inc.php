<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-06-02
	 * Time: 21:54
	 */
	require_once '../services/DatabaseService.php';
	require_once '../services/UserService.php';
	require_once '../consts.php';
	
	class AuthSignUp
	{
		
		function signUp($userName, $email, $password, $passwordRepeat)
		{
			if (empty($userName) || empty($email) || empty($password) || empty($passwordRepeat)) {
				print_r(json_encode(array('status' => CODE_BAD_REQUEST, 'msg' => 'Bad request, user registration failed')));
				exit();
			} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				print_r(json_encode(array('status' => CODE_BAD_REQUEST, 'msg' => 'Invalid email')));
				exit();
			} else if (!preg_match('/^[a-zA-Z0-9]*$/', $userName)) {
				print_r(json_encode(array('status' => CODE_BAD_REQUEST, 'msg' => 'Invalid username')));
				exit();
			} else if ($password !== $passwordRepeat) {
				print_r(json_encode(array('status' => CODE_BAD_REQUEST, 'msg' => 'Password does not matched')));
				exit();
			} else {
				$conn = DatabaseService::getInstance()->connect();
				$sql = 'SELECT username, email from users WHERE username = ? or email = ?';
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					print_r(json_encode(array('status' => CODE_SERVER_ERROR, 'msg' => 'Server error')));
					exit();
				} else {
					mysqli_stmt_bind_param($stmt, 'ss', $userName, $email);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					if ($row = mysqli_fetch_assoc($result)) {
						if ($row['username'] === $userName || $row['email'] === $email) {
							print_r(json_encode(array('status' => CODE_BAD_REQUEST, 'msg' => 'User is taken')));
							exit();
						}
					} else {
						$sql = 'INSERT INTO users(deleted, email, password, username, role, created_date) VALUES (?, ?, ?, ?, ?, ?)';
						$stmt = mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stmt, $sql)) {
							print_r(json_encode(array('status' => CODE_SERVER_ERROR, 'msg' => 'Server error')));
							exit();
						} else {
							$deleted = 0;
							$role = USER_NORMAL;
							$dateTime = gmdate(TIME_FORMAT);
							$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
							
							mysqli_stmt_bind_param($stmt, 'ssssss', $deleted, $email, $hashedPwd, $userName, $role, $dateTime);
							mysqli_stmt_execute($stmt);
							print_r(json_encode(array('status' => CODE_SUCCESS, 'msg' => 'Thank you. To complete your registration please check your email.')));
							exit();
						}
					}
				}
			}
			mysqli_stmt_close($stmt);
			mysqli_close($conn);
		}
	}