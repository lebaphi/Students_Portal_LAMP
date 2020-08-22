<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-06-29
	 * Time: 10:05
	 */
	require_once(__DIR__ . '/../assets/vendors/custom/jwt/JWT.php');
	require_once(__DIR__ . '/../consts.php');
	
	class CurrentUser
	{
		/**
		 * @param $user
		 * @param $remember
		 */
		function setUser($user, $remember)
		{
			$_SESSION['currentUser'] = $user;
			$_SESSION['remember'] = $remember;
			$_SESSION['isAuthenticated'] = true;
			$_SESSION['viewMode'] = false;
			$this->extendSession();
		}
		
		/**
		 * extend session
		 */
		function extendSession()
		{
			$token = array();
			$token['id'] = $this->getId();
			$token['email'] = $this->getEmail();
			$token['nbf'] = strtotime("now");
			$token['exp'] = strtotime("+7200 second");
			$_SESSION['sessionId'] = JWT::encode($token, SERVER_KEY);
			UserService::getInstance()->updateSessionId(array('session_id' => $this->getSessionId(), 'id' => $this->getId()));
		}
		
		/**
		 * @return mixed
		 */
		function getSessionId()
		{
			if (isset($_SESSION['sessionId'])) {
				return $_SESSION['sessionId'];
			}
			return null;
		}
		
		/**
		 * @return mixed
		 */
		function isAuthenticated()
		{
			return isset($_SESSION['isAuthenticated']);
		}
		
		/**
		 * @return string
		 */
		function getId()
		{
			return $_SESSION['currentUser']['id'];
		}
		
		/**
		 * @return mixed
		 */
		function getUserName()
		{
			return $_SESSION['currentUser']['username'];
		}
		
		/**
		 * @return mixed
		 */
		function getEmail()
		{
			return $_SESSION['currentUser']['email'];
		}
		
		/**
		 * @return mixed
		 */
		function getRole()
		{
			return $_SESSION['currentUser']['role'];
		}
		
		/**
		 * @return mixed
		 */
		function getFirstName()
		{
			return $_SESSION['currentUser']['first_name'];
		}
		
		/**
		 * @param $firstName
		 */
		function setFirstName($firstName)
		{
			$_SESSION['currentUser']['first_name'] = $firstName;
		}
		
		/**
		 * @return mixed
		 */
		function getLastName()
		{
			return $_SESSION['currentUser']['last_name'];
		}
		
		/**
		 * @param $lastName
		 */
		function setLastName($lastName)
		{
			$_SESSION['currentUser']['last_name'] = $lastName;
		}
		
		/**
		 * @return string
		 */
		function getFullName()
		{
			return $this->getFirstName() . ' ' . $this->getLastName();
		}
		
		/**
		 * @return mixed
		 */
		function getCompany()
		{
			return $_SESSION['currentUser']['company'];
		}
		
		/**
		 * @param $company
		 */
		function setCompany($company)
		{
			$_SESSION['currentUser']['company'] = $company;
		}
		
		/**
		 * @return mixed
		 */
		function getTitle()
		{
			return $_SESSION['currentUser']['title'];
		}
		
		/**
		 * @param $title
		 */
		function setTitle($title)
		{
			$_SESSION['currentUser']['title'] = $title;
		}
		
		/**
		 * @return mixed
		 */
		function getPhone()
		{
			return $_SESSION['currentUser']['phone'];
		}
		
		/**
		 * @param $phone
		 */
		function setPhone($phone)
		{
			$_SESSION['currentUser']['phone'] = $phone;
		}
		
		/**
		 * @return mixed
		 */
		function getAddress()
		{
			return $_SESSION['currentUser']['address'];
		}
		
		/**
		 * @param $address
		 */
		function setAddress($address)
		{
			$_SESSION['currentUser']['address'] = $address;
		}
		
		/**
		 * @return mixed
		 */
		function getCity()
		{
			return $_SESSION['currentUser']['city'];
		}
		
		/**
		 * @return mixed
		 */
		function getProvince()
		{
			return $_SESSION['currentUser']['province'];
		}
		
		/**
		 * @return mixed
		 */
		function getPostalCode()
		{
			return $_SESSION['currentUser']['postal_code'];
		}
		
		/**
		 * @return mixed
		 */
		function getCountry()
		{
			return $_SESSION['currentUser']['country'];
		}
		
		/**
		 * @return mixed
		 */
		function isDeleted()
		{
			return $_SESSION['currentUser']['deleted'];
		}
		
		/**
		 * @return bool
		 */
		function isUser()
		{
			return $this->getRole() === USER_NORMAL;
		}
		
		/**
		 * @return bool
		 */
		function isUserManager()
		{
			return $this->getRole() === USER_MANAGER;
		}
		
		/**
		 * @return bool
		 */
		function isAdmin()
		{
			return $this->getRole() === ADMIN;
		}
		
		/**
		 * @return mixed|string
		 */
		function getDisplayName()
		{
			if (empty($this->getFirstName()) || empty($this->getLastName())) {
				return $this->getUserName();
			}
			return $this->getFirstName() . ' ' . ucwords($this->getLastName()[0]) . '.';
		}
		
		/**
		 * @return mixed
		 */
		function isRemember()
		{
			return $_SESSION['remember'];
		}
	}