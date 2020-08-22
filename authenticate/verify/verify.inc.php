<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-12-01
	 * Time: 21:54
	 */
	require_once '../services/DatabaseService.php';
	require_once '../services/UserService.php';
	require_once '../consts.php';
	
	class AuthVerify
	{
		function verify($email, $hashPwd)
		{
			$sql = 'UPDATE users SET deleted = 0 WHERE md5(email) = ? AND md5(password) = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($email, $hashPwd));
		}
	}