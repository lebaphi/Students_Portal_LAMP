<?php
	
	require_once (__DIR__ . '/../assets/vendors/custom/phpdotenv/vendor/autoload.php');
	
	(Dotenv\Dotenv::create(__DIR__ . '/../'))->load();
	
	
	class EnvService
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
		 * @param $key
		 * @return mixed
		 */
		function getEnvByKey($key)
		{
			return getenv($key);
		}
	}