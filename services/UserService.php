<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-03-27
	 * Time: 20:46
	 */
	require_once 'DatabaseService.php';
	require_once 'CurrentUser.php';
	
	class UserService
	{
		
		private static $_instance = null;
		private static $_currentUser = null;
		
		static function getInstance()
		{
			if (self::$_instance !== null) {
				return self::$_instance;
			}
			self::$_instance = new self();
			self::$_currentUser = new CurrentUser();
			return self::$_instance;
		}
		
		/**
		 * @return CurrentUser
		 */
		function getCurrentUser()
		{
			return self::$_currentUser;
		}
		
		/**
		 * @param $include_soft_user
		 * @return bool|mysqli_result|null
		 */
		function getAllUsers()
		{
			if ($this->getCurrentUser()->isAdmin()) {
				$roles = array("admin", "user");
				$role_array = '"' . implode('","', $roles) . '"';
				$sql = 'SELECT * FROM users WHERE role IN (' . $role_array . ') ORDER BY last_logged_in DESC';
				return DatabaseService::getInstance()->doQuery($sql, array());
			} else {
				$sql = 'SELECT * FROM users WHERE role IN ("NONE") ORDER BY last_logged_in DESC';
				return DatabaseService::getInstance()->doQuery($sql, array());
			}
		}
		
		/**
		 * @param $uId
		 * @return bool|mysqli_result|null
		 */
		function deleteUserById($uId)
		{
			$sql = 'DELETE FROM users WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($uId));
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function updateUser($data)
		{
			$sql = 'UPDATE users SET title = ?, first_name = ?, last_name = ?, role = ?, deleted = ?, address = ?, city = ?, province = ?, country = ?, company = ?, phone = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['title'], $data['first_name'], $data['last_name'], $data['role'], $data['deleted'], $data['address'], $data['city'], $data['province'], $data['country'], $data['company'], $data['phone'], $data['id']));
		}
		
		/**
		 * @param $email
		 * @return bool|mysqli_result|null
		 */
		function getUserByEmail($email)
		{
			$sql = 'SELECT * FROM users WHERE email = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($email));
		}
		
		/**
		 * @param $userId
		 * @return array|null
		 */
		function getUserById($userId)
		{
			$sql = 'SELECT * FROM users WHERE id = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($userId));
			return mysqli_fetch_assoc($result);
		}
		
		/**
		 * @param $userId
		 * @return array|null
		 */
		function getSessionId($userId)
		{
			$sql = 'SELECT session_id from users WHERE id = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($userId));
			return mysqli_fetch_assoc($result)['session_id'];
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function updateSessionId($data)
		{
			$sql = 'UPDATE users SET session_id = ?, last_logged_in = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['session_id'], gmdate(TIME_FORMAT), $data['id']));
		}
		
		/**
		 * add new student
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function addStudent($data)
		{
			$mode = $data['mode'];
			if ($mode === 'add') {
				$sql = 'INSERT INTO students (gender, religion, nationality, residence_country, data, residence_city, cell_number, email, computer, english, last_degree, education_level, specialization, inst_level_name, inst_name, grade_name, course, remarks_by_akeb, academic_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
				return DatabaseService::getInstance()->doQuery($sql, array($data['gender'], $data['religion'], $data['nationality'], $data['residence_country'], $data['data'], $data['residence_city'], $data['cell_number'], $data['email'], $data['computer'], $data['english'], $data['last_degree'], $data['education_level'], $data['specialization'], $data['inst_level_name'], $data['inst_name'], $data['grade_name'], $data['course'], $data['remarks_by_akeb'], $data['academic_year']));
			} else if ($mode === 'update') {
				$sql = 'UPDATE students SET gender = ?, religion = ?, nationality = ?, residence_country = ?, data = ?, residence_city = ?, cell_number = ?, email = ?, computer = ?, english = ?, last_degree = ?, education_level = ?, specialization = ?, inst_level_name = ?, inst_name = ?, grade_name = ?, course = ?, remarks_by_akeb = ?, academic_year = ? WHERE id = ?';
				return DatabaseService::getInstance()->doQuery($sql, array($data['gender'], $data['religion'], $data['nationality'], $data['residence_country'], $data['data'], $data['residence_city'], $data['cell_number'], $data['email'], $data['computer'], $data['english'], $data['last_degree'], $data['education_level'], $data['specialization'], $data['inst_level_name'], $data['inst_name'], $data['grade_name'], $data['course'], $data['remarks_by_akeb'], $data['academic_year'], $data['id']));
			}
		}
		
		/**
		 * get all students
		 * @return bool|mysqli_result|null
		 */
		function getStudents(){
			$sql = 'SELECT * FROM students WHERE deleted = 0';
			return DatabaseService::getInstance()->doQuery($sql, array());
		}
		
		/**
		 * remove student
		 * @param $id
		 * @return bool|mysqli_result|null
		 */
		function removeStudent($id){
			$sql = 'DELETE FROM students WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($id));
		}
		
		/**
		 * get student by id
		 * @param $id
		 * @return bool|mysqli_result|null
		 */
		function getStudentById($id) {
			$sql = 'SELECT * FROM students WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($id));
		}
	}