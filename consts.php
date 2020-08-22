<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-03-31
	 * Time: 18:51
	 */
	
	define('CODE_SUCCESS', 200);
	define('CODE_BAD_REQUEST', 400);
	define('CODE_UNAUTHORIZED', 401);
	define('CODE_SERVER_ERROR', 500);
	define('CODE_SERVER_FORBIDDEN', 403);
	
	define('ADMIN', 'admin');
	
	define('ACCESS_DENIED', '0x00');
	define('SQL_ERROR', '0x01');
	define('TIME_FORMAT', 'Y-m-d H:i:s');
	
	define('TIME_TO_EXPIRE', 7200);
	define('LIMIT_LOGIN_TIME', 5);
	define('BLOCK_TIME_IN_MINUTE', 15);
	
	define('RESULT_OK', 'ok');
	define('RESULT_FAIL', 'failed');
	define('MSG_SUCCESS', 'Success');
	define('MSG_FAILED', 'Failed');
	
	define('SERVER_KEY', '5a2b5ldbe5194g10b32d1568fe4e2b25');
	define('SERVER_HOST', $_SERVER['HTTP_HOST']);
	
	define('MSG_DONT_HAVE_PERMISSION', 'You don\'t have permission to operate this action');
	
	define('ACTION_KEY', 'action');