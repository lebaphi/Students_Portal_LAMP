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
		function isViewMode()
		{
			return $_SESSION['viewMode'];
		}
		
		/**
		 * @param $viewMode
		 */
		function setViewMode($viewMode)
		{
			$_SESSION['viewMode'] = $viewMode;
		}
		
		/**
		 * store last user token
		 */
		function storeLastUserId()
		{
			$_SESSION['lastUserId'] = $this->getId();
			$_SESSION['lastUserRole'] = $this->getRole();
		}
		
		/**
		 * @return mixed
		 */
		function getLastUserId()
		{
			return $_SESSION['lastUserId'];
		}
		
		/**
		 * @return mixed
		 */
		function getLastUserRole()
		{
			return $_SESSION['lastUserRole'];
		}
		
		/*
		 * reset last store session
		 */
		function resetLastIdStore()
		{
			$_SESSION['lastUserId'] = null;
			$_SESSION['lastUserRole'] = null;
		}
		
		/**
		 * @param $user
		 */
		function setNewUserSession($user)
		{
			self::storeLastUserId();
			self::setUser($user, $this->isRemember());
			self::setViewMode(true);
		}
		
		/**
		 * restore user token
		 */
		function restoreLastSession()
		{
			try {
				$lastUser = UserService::getInstance()->getUserById($this->getLastUserId());
				self::setUser($lastUser, $this->isRemember());
				self::setViewMode(false);
				self::resetLastIdStore();
			} catch (Exception $e) {
				session_unset();
				session_destroy();
				NotificationService::getInstance()->errorThenReload('Your session has expired');
				exit();
			}
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
		function getClientId()
		{
			return $_SESSION['currentUser']['client_id'];
		}
		
		/**
		 * @param $client_id
		 */
		function setClientId($client_id)
		{
			$_SESSION['currentUser']['client_id'] = $client_id;
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
		function getAddress2()
		{
			return $_SESSION['currentUser']['address2'];
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
		function getAvatar()
		{
			return $_SESSION['currentUser']['avatar'];
		}
		
		/**
		 * @param $avatar
		 */
		function setAvatar($avatar)
		{
			$_SESSION['currentUser']['avatar'] = $avatar;
		}
		
		/**
		 * @return mixed
		 */
		function isDeleted()
		{
			return $_SESSION['currentUser']['deleted'];
		}
		
		/**
		 * @return mixed
		 */
		function getTerm()
		{
			return $_SESSION['currentUser']['term'];
		}
		
		/**
		 * @param $term
		 */
		function setTerm($term)
		{
			$_SESSION['currentUser']['term'] = $term;
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
		 * @return bool
		 */
		function isGhostUser()
		{
			return $this->getRole() === GHOST_USER;
		}
		
		/**
		 * @return bool
		 */
		function isConsultant()
		{
			return $this->getRole() === CONSULTANT;
		}
		
		/**
		 * @return bool
		 */
		function isClientUser()
		{
			return strtolower($_SESSION['currentUser']['role']) === 'user manager';
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
		 * @param $name
		 */
		function setGhostName($name)
		{
			$_SESSION['currentUser']['ghost_name'] = $name;
		}
		
		/**
		 * @return mixed
		 */
		function getGhostName()
		{
			return $_SESSION['currentUser']['ghost_name'];
		}
		
		/**
		 * @param $email
		 */
		function setGhostEmail($email)
		{
			$_SESSION['currentUser']['ghost_email'] = $email;
		}
		
		/**
		 * @return mixed
		 */
		function getGhostEmail()
		{
			return $_SESSION['currentUser']['ghost_email'];
		}
		
		/**
		 * @param $id
		 */
		function setGhostId($id)
		{
			$_SESSION['currentUser']['ghost_id'] = $id;
		}
		
		/**
		 * @param $id
		 * @return mixed
		 */
		function getGhostId()
		{
			return $_SESSION['currentUser']['ghost_id'];
		}
		
		/**
		 * @return mixed
		 */
		function isRemember()
		{
			return $_SESSION['remember'];
		}
	}