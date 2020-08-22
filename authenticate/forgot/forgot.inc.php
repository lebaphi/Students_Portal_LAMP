<?php
	require_once '../consts.php';
	require_once '../services/UserService.php';
	require_once '../services/EnvService.php';
	require_once '../services/MailerService.php';
	
	class ForgotPassword
	{
		
		function requestPassword($email)
		{
			$user = UserService::getInstance()->getUserByEmail($email);
			if ($user->num_rows > 0) {
				$serverApi = EnvService::getInstance()->getEnvByKey('SERVER_API');
				foreach ($user as $u) {
					$url = SERVER_HOST . $serverApi . '/authenticate/auth.php?key=' . md5($u['email']) . '&reset=' . md5($u['password']);
					$content = "<a href='$url'>Click here to reset password</a>";
					try {
						$mailer = MailerService::getInstance()->initMailer();
						$mailer->addAddress($email);
						$mailer->Body = $content;
						$mailer->Subject = 'Reset Password';
						$mailer->send();
						print_r(json_encode(array('result' => RESULT_OK, 'msg' => 'Mail has been sent')));
					} catch (Exception $e) {
						print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'Unable to send mail')));
					}
				}
			} else {
				print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'User does not exist')));
			}
		}
	}