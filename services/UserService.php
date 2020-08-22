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
		function getAllUsers($include_soft_user = false)
		{
			if ($this->getCurrentUser()->isAdmin() ||
				$this->getCurrentUser()->isConsultant()) {
				$roles = array("admin", "consultant", "user", "user manager");
				if ($include_soft_user) {
					array_push($roles, 'soft user');
				}
				$role_array = '"' . implode('","', $roles) . '"';
				$sql = 'SELECT * FROM users WHERE role IN (' . $role_array . ') ORDER BY last_logged_in DESC';
				return DatabaseService::getInstance()->doQuery($sql, array());
			} else {
				$sql = 'SELECT * FROM users WHERE role IN ("NONE") ORDER BY last_logged_in DESC';
				return DatabaseService::getInstance()->doQuery($sql, array());
			}
		}
		
		/**
		 * @param $email
		 * @return bool|mysqli_result|null
		 */
		function getUserByInfo($email)
		{
			$sql = 'SELECT * FROM users WHERE email = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($email));
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
		 * @return array|null
		 */
		function getGhostUser()
		{
			$sql = 'SELECT * FROM users WHERE deleted = ? AND role = ? AND password = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array('1', GHOST_USER, GHOST_PASS));
			if (mysqli_num_rows($result) > 0) {
				return mysqli_fetch_assoc($result);
			} else {
				return null;
			}
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function setViewClientId($data)
		{
			$sql = 'UPDATE users SET client_id = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['client_id'], $data['user_id']));
		}
		
		/**
		 * @param $md5Email
		 * @param $md5Password
		 * @return bool
		 */
		function verifyResetPassword($md5Email, $md5Password)
		{
			$sql = 'SELECT * FROM users WHERE md5(email) = ? AND md5(password) = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($md5Email, $md5Password));
			return mysqli_num_rows($result) > 0;
		}
		
		/**
		 * @param $md5Email
		 * @param $newPassword
		 * @return bool|mysqli_result|null
		 */
		function resetPassword($md5Email, $newPassword)
		{
			$sql = 'UPDATE users SET password = ? WHERE md5(email) = ?';
			$hashPass = password_hash($newPassword, PASSWORD_DEFAULT);
			return DatabaseService::getInstance()->doQuery($sql, array($hashPass, $md5Email));
		}
		
		/**
		 * @param $args
		 * @param $mode
		 * @return bool|mysqli_result|null
		 */
		function getUsersByRole($args, $mode)
		{
			$roles = '"' . implode('","', $args) . '"';
			if ($mode === UNASSIGNED_CLIENT_MODE) {
				$sql = 'SELECT * FROM users WHERE role IN (' . $roles . ') AND deleted = 0';
			} else {
				$sql = 'SELECT * FROM users WHERE role IN (' . $roles . ') AND client_id = "" AND deleted = 0';
			}
			return DatabaseService::getInstance()->doQuery($sql, array());
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function getUsersByClient($data)
		{
			$client_arr_ids = implode(',', $data['client_id']);
			if ($data['mode'] === ADMIN_SIDE) {
				$sql = 'SELECT id, username, email, role, created_date, deleted FROM users WHERE client_id IN (' . $client_arr_ids . ') AND role IN ("user", "user manager") ORDER BY created_date DESC';
			} else if ($data['mode'] === CLIENT_SIDE) {
				$sql = 'SELECT id, avatar, username, first_name, last_name, email, role, created_date, deleted FROM users WHERE client_id IN (' . $client_arr_ids . ') AND role IN ("user", "user manager", "soft user") ORDER BY created_date DESC';
			}
			return DatabaseService::getInstance()->doQuery($sql, array());
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
		function archiveUser($data)
		{
			$sql = 'UPDATE users SET deleted = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['deleted'], $data['user_id']));
		}
		
		/**
		 * @param $userId
		 * @return bool|mysqli_result|null
		 */
		function getClientByUserId($userId)
		{
			$sql = 'SELECT client_id FROM users WHERE id = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($userId));
			return mysqli_fetch_assoc($result);
		}
		
		/**
		 * @param $userId
		 * @return bool|mysqli_result|null
		 */
		function removeUserFromClient($userId)
		{
			$sql = 'UPDATE users SET client_id = \'\' WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($userId));
		}
		
		/**
		 * @param $clientId
		 * @param $userId
		 * @return bool|mysqli_result|null
		 */
		function assignUserToClient($clientId, $userId)
		{
			$sql = 'UPDATE users SET client_id = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($clientId, $userId));
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function updateUser($data)
		{
			$sql = 'UPDATE users SET title = ?, first_name = ?, last_name = ?, role = ?, deleted = ?, address = ?, address2 = ?, city = ?, province = ?, postal_code = ?, country = ?, company = ?, phone = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['title'], $data['first_name'], $data['last_name'], $data['role'], $data['deleted'], $data['address'], $data['address2'], $data['city'], $data['province'], $data['postal_code'], $data['country'], $data['company'], $data['phone'], $data['id']));
		}
		
		/**
		 * @param $data
		 * @return bool
		 */
		function updateUserProfile($data)
		{
			$sql = 'UPDATE users SET title = ?, first_name = ?, last_name = ?, phone = ?, address = ?, address2 = ?, city = ?, province = ?, postal_code = ?, country = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['title'], $data['first_name'], $data['last_name'], $data['phone'], $data['address'], $data['address2'], $data['city'], $data['province'], $data['postal_code'], $data['country'], $data['id']));
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function updateUserAvatar($data)
		{
			$sql = 'UPDATE users SET avatar = ? WHERE id = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($data['avatar'], $data['id']));
			if ($result) {
				self::getCurrentUser()->setAvatar($data['avatar']);
			}
			return $result;
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function addUser($data)
		{
			$sql = 'INSERT INTO users (email, username, password, role, created_date, deleted, term, first_name, last_name, client_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
			return DatabaseService::getInstance()->doQuery($sql, array($data['email'], $data['username'], $data['password'], $data['role'], $data['createdDate'], $data['deleted'], $data['term'], $data['first_name'], $data['last_name'], $data['user_client']));
		}
		
		/**
		 * @param $data
		 * @return bool
		 */
		function verifyPassword($data)
		{
			$sql = 'SELECT password from users WHERE id = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($data['user_id']));
			if ($result) {
				$result = mysqli_fetch_assoc($result);
				return password_verify($data['current_pass'], $result['password']);
			}
			return false;
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function changePassword($data)
		{
			if ($this->verifyPassword($data)) {
				$sql = 'UPDATE users SET password = ? WHERE id = ?';
				$hashPass = password_hash($data['new_pass'], PASSWORD_DEFAULT);
				return DatabaseService::getInstance()->doQuery($sql, array($hashPass, $data['user_id']));
			}
			return false;
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
		 * @param $userId
		 * @return bool|mysqli_result|null
		 */
		function updateUserTerm($userId)
		{
			$sql = 'UPDATE users SET term = 1 WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($userId));
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function addTrainingUser($data)
		{
			$sql = 'INSERT INTO users (first_name, last_name, email, password, client_id, role, created_date, deleted, term) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
			return DatabaseService::getInstance()->doQuery($sql, array($data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['client_id'], $data['role'], $data['createdDate'], $data['deleted'], $data['term']));
		}
		
		/**
		 * @param $data
		 * @return array
		 */
		function getLoginHistory($data)
		{
			$sql = 'SELECT login_count, created_date FROM authenticate_security WHERE email = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($data['email']));
			if (mysqli_num_rows($result) === 0) {
				$sql = 'INSERT INTO authenticate_security (ip_address, email, login_count, created_date) VALUES (?, ?, ?, ?)';
				DatabaseService::getInstance()->doQuery($sql, array($data['ip_address'], $data['email'], $data['login_count'], $data['created_date']));
				return array('login_count' => 0, 'block_time' => gmdate(TIME_FORMAT));
			}
			$result = mysqli_fetch_assoc($result);
			return array('login_count' => $result['login_count'], 'block_time' => $result['created_date']);
		}
		
		/**
		 * @param $data
		 * @return bool|false|mysqli_result|null
		 */
		function updateLoginCount($data)
		{
			$sql = 'UPDATE authenticate_security SET login_count = ?, created_date = ? WHERE email = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['count'], $data['created_date'], $data['email']));
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function getUserForBackupConsultant($data)
		{
			$sql = 'SELECT * FROM users WHERE id != ? AND id NOT IN (SELECT backup_consultant_id FROM backup_consultant WHERE client_id = ?) AND id NOT IN (SELECT consultant_user_id FROM clients WHERE id = ?) AND deleted = 0 AND role IN ("consultant", "admin")';
			return DatabaseService::getInstance()->doQuery($sql, array($data['current_user_id'], $data['client_id'], $data['client_id']));
		}
		
		/**
		 * @param $consultant_id
		 * @param $backup_consultant_id
		 * @param $client_id
		 * @return bool
		 */
		function isBackupConsultantExist($consultant_id, $backup_consultant_id, $client_id)
		{
			$sql = 'SELECT * FROM backup_consultant WHERE consultant_id = ? AND backup_consultant_id = ? AND client_id = ?';
			$result = DatabaseService::getInstance()->doQuery($sql, array($consultant_id, $backup_consultant_id, $client_id));
			if (mysqli_num_rows($result) > 0) {
				return true;
			}
			return false;
		}
		
		/**
		 * @param $data
		 */
		function saveBackupConsultant($data)
		{
			foreach ($data['bk_consultant_ids'] as $backup_consultant_id) {
				foreach ($data['client_list'] as $client_id) {
					if ($this->isBackupConsultantExist($data['consultant_user_id'], $backup_consultant_id, $client_id)) {
						continue;
					}
					$sql = 'INSERT INTO backup_consultant (consultant_id, backup_consultant_id, client_id, created_date) VALUES (?, ?, ?, ?)';
					DatabaseService::getInstance()->doQuery($sql, array($data['consultant_user_id'], $backup_consultant_id, $client_id, $data['created_date']));
				}
			}
		}
		
		/**
		 * @param $client_id
		 * @return bool|mysqli_result|null
		 */
		function getAllBackupConsultants($client_id)
		{
			$sql = 'SELECT * FROM users WHERE id IN (SELECT backup_consultant_id FROM backup_consultant WHERE client_id = ?) AND deleted = 0';
			return DatabaseService::getInstance()->doQuery($sql, array($client_id));
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function removeBackupConsultant($data)
		{
			$id_arr = implode(',', $data);
			$sql = 'DELETE FROM backup_consultant WHERE backup_consultant_id IN (' . $id_arr . ')';
			return DatabaseService::getInstance()->doQuery($sql, array());
		}
	}