<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-03-31
	 * Time: 19:15
	 */
	
	require_once 'EnvService.php';
	
	class DatabaseService
	{
		private static $_instance = null;
		private static $lastInsertId = '';
		
		static function getInstance()
		{
			if (self::$_instance !== null) {
				return self::$_instance;
			}
			self::$_instance = new self();
			return self::$_instance;
		}
		
		function connect()
		{
			$env = EnvService::getInstance();
			$conn = mysqli_connect(
				$env->getEnvByKey('DB_HOST'),
				$env->getEnvByKey('DB_USER'),
				$env->getEnvByKey('DB_PWD'),
				$env->getEnvByKey('DB_NAME')
			);
			
			if (!$conn) {
				die('Connection failed: ' . mysqli_connect_error());
			}
			return $conn;
		}
		
		/**
		 * @param $sql
		 * @param $args
		 * @return bool|mysqli_result|null
		 */
		function doQuery($sql, $args)
		{
			$result = false;
			$hasError = false;
			$lastError = array();
			
			$conn = DatabaseService::getInstance()->connect();
			$stmt = mysqli_stmt_init($conn);
			if (mysqli_stmt_prepare($stmt, $sql)) {
				if (count($args) > 0) {
					$types = str_repeat('s', count($args));
					mysqli_stmt_bind_param($stmt, $types, ...$args);
				}
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				self::$lastInsertId = mysqli_stmt_insert_id($stmt);
				$lastError = mysqli_stmt_error_list($stmt);
				$hasError = count($lastError) > 0;
			}
			mysqli_stmt_close($stmt);
			mysqli_close($conn);
			if (!$hasError) {
				return !$result ? true : $result;
			} else {
				$this->verifyFailedResult($lastError);
			}
		}
		
		/**
		 * @return mixed
		 */
		function getLastId()
		{
			return self::$lastInsertId;
		}
		
		/**
		 * @param $lastErrors
		 */
		function verifyFailedResult($lastErrors)
		{
			foreach ($lastErrors as $lastError) {
				print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => $lastError['error'])));
				exit();
			}
		}
	}