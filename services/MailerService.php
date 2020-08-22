<?php
	
	require_once(__DIR__ . '/../assets/vendors/custom/aws/aws-autoloader.php');
	require_once '../../consts.php';
	require_once 'EnvService.php';
	require_once 'S3Service.php';
	
	use Aws\Ses\SesClient;
	use Aws\Exception\AwsException;
	
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
			$S3Credential = S3Service::getInstance()->getS3Credential();
			if ($S3Credential === null) {
				print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'User to load S3 credentials')));
				exit();
			}
			$SesClient = new SesClient([
				'http' => [
					'connect_timeout' => 10
				],
				'version' => 'latest',
				'region' => 'us-east-1',
				'credentials' => [
					'key' => $S3Credential['access_key_id'],
					'secret' => $S3Credential['secret_key_id'],
				]]);
			
			try {
				$SesClient->sendEmailAsync([
					'Destination' => [
						'ToAddresses' => $data['recipient'],
					],
					'ReplyToAddresses' => ['support@tickner360.com'],
					'Source' => 'support@tickner360.com',
					'Message' => [
						'Body' => [
							'Html' => [
								'Charset' => 'UTF-8',
								'Data' => $data['body'],
							]
						],
						'Subject' => [
							'Charset' => 'UTF-8',
							'Data' => $data['subject'],
						],
					],
				]);
			} catch (AwsException $e) {
				print_r("The email was not sent. Error message: " . $e->getAwsErrorMessage() . "\n");
			}
		}
		
		/**
		 * @param $email
		 * @param $data
		 */
		function sendAddedUserInfo($email, $data)
		{
			$recipient = [$email];
			$subject = 'User Notification';
			$body = '<h3>You have been successfully added to T360</h3><table><tr><td>Email</td><td>: ' . $email . '</td></tr><tr><td>Password</td><td>: ' . $data['user_password'] . '</td></tr></table><br>Best Regards,<br>T360\'s Support Team';
			$data = array('recipient' => $recipient, 'body' => $body, 'subject' => $subject);
			$this->send($data);
		}
		
		/**
		 * @param $email
		 * @param $rawPwd
		 * @param $hashPwd
		 */
		function sendVerificationEmail($email, $rawPwd, $hashPwd)
		{
			$recipient = [$email];
			$subject = 'Verification email';
			$serverApi = EnvService::getInstance()->getEnvByKey('SERVER_API');
			$activeLink = SERVER_HOST . $serverApi . '/authenticate/auth.php?verifyEmail=true&email=' . md5($email) . '&hash=' . md5($hashPwd);
			$body = 'Hello, ' . $email . ', <br><br>You\'re account has been successfully created.
			<br>Email: ' . $email . '<br><br>Password: ' . $rawPwd . '
			<br><br>To activate your account, Please click the link: <a href="' . $activeLink . '">here</a><br>
			If you have not created this account, please ignore this message.<br><br>
			<br>T360\'s Support Team';
			$data = array('recipient' => $recipient, 'body' => $body, 'subject' => $subject);
			$this->send($data);
		}

		function sendLostPasswordEmail($email, $rawPwd, $hashPwd)
		{
			$recipient = [$email];
			$subject = 'Lost Password Link';
			$body = '';
			$data = array('recipient' => $recipient, 'body' => $body, 'subject' => $subject);
			$this->send($data);
		}


		#must opt in, must delay send for 5mins after file been uloaded
		function sendManagersUploadNotifications($email, $rawPwd, $hashPwd)
		{
			$recipient = [$email];
			$subject = 'File Uploaded to your email';
			$body = 'Hello, The User Has uploaded a file. The status is $Active';
			$data = array('recipient' => $recipient, 'body' => $body, 'subject' => $subject);
			$this->send($data);
		}
	}