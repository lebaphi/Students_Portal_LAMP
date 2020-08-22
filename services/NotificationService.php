<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-03-30
	 * Time: 10:00
	 */
	
	require_once 'DatabaseService.php';
	
	class NotificationService
	{
		private static $_instance = null;
		
		static function getInstance()
		{
			if (self::$_instance !== null) {
				return self::$_instance;
			}
			
			self::$_instance = new self();
			return self::$_instance;
		}
		
		/**
		 * console log
		 * @param $msgText
		 */
		function console($msgText)
		{
			echo '<script>console.log("' . $msgText . '")</script>';
		}
		
		/**
		 * alert
		 * @param $msgText
		 */
		function alert($msgText)
		{
			echo '<script>swal("' . $msgText . '")</script>';
		}
		
		/**
		 * toast success
		 * @param $msgText
		 */
		function success($msgText)
		{
			echo '<script>toastr.success("' . $msgText . '")</script>';
		}
		
		/**
		 * toast error
		 * @param $msgText
		 */
		function error($msgText)
		{
			echo '<script>toastr.error("' . $msgText . '");</script>';
		}
		
		/**
		 * toast info
		 * @param $msgText
		 */
		function info($msgText)
		{
			echo '<script>toastr.info("' . $msgText . '");</script>';
		}
		
		/**
		 * toast warning
		 * @param $msgText
		 */
		function warning($msgText)
		{
			echo '<script>toastr.warning("' . $msgText . '");</script>';
		}
		
		/**
		 * @param $msgText
		 */
		function errorThenReload($msgText)
		{
			echo '<script>handleSignOut(); toastr.error("' . $msgText . '");setTimeout(()=> location.reload(), 1500)</script>';
		}
		
		/**
		 * @return bool|mysqli_result|null
		 */
		function getUserNotification()
		{
			$sql = 'SELECT * FROM user_notifications order by created_date DESC';
			return DatabaseService::getInstance()->doQuery($sql, array());
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|null
		 */
		function setReadNotification($data)
		{
			$sql = 'UPDATE user_notifications SET is_new = ? WHERE id = ?';
			return DatabaseService::getInstance()->doQuery($sql, array($data['notification_data'], $data['notification_id']));
		}
		
		/**
		 * @param $data
		 * @return bool|mysqli_result|nulls
		 */
		function addNotification($data)
		{
			$sql = 'INSERT INTO user_notifications (msg, is_new, created_date, created_by) VALUES (?, ?, ?, ?)';
			return DatabaseService::getInstance()->doQuery($sql, array($data['msg'], $data['is_new'], $data['created_date'], $data['created_by']));
		}
	}