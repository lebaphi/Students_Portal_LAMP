<?php
	
	require_once '../../consts.php';
	require_once 'EnvService.php';
	
	class MailerService
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
		 * @param $data
		 */
		function send($data)
		{
			print_r($data);
		}
	}