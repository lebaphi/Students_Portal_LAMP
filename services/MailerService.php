<?php
	
	require_once '../../consts.php';
	require_once 'EnvService.php';
	require_once 'UserService.php';
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	require '../../assets/vendors/custom/phpmailer/src/Exception.php';
	require '../../assets/vendors/custom/phpmailer/src/PHPMailer.php';
	require '../../assets/vendors/custom/phpmailer/src/SMTP.php';
	
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
			$mail = new PHPMailer(true);
			try {
				//Server settings
				$mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
				$mail->isSMTP();                                            // Send using SMTP
				$mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				$mail->Username   = 'sharmasherlander@gmail.com';           // SMTP username
				$mail->Password   = 'Sharma123@';                               // SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
				
				//Recipients
				$mail->setFrom('sharmasherlander@gmail.com', 'Mailer');
				$mail->addAddress(UserService::getInstance()->getCurrentUser()->getEmail());      // Name is optional
				
				// Content
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = 'Notification';
				$mail->Body    = $data;
				
				$mail->send();
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
	}