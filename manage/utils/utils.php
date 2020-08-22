<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-04-07
	 * Time: 17:24
	 */
	
	require_once '../../assets/vendors/custom/jwt/JWT.php';
	require_once '../../services/UserService.php';
	require_once '../../services/DatabaseService.php';
	require_once '../../services/NotificationService.php';
	require_once '../../consts.php';
	
	
	/**
	 * @param $request
	 */
	function sanitizeRequest($request)
	{
		foreach ($request as $key => $val) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$_POST[$key] = strip_tags($val);
			} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				$_GET[$key] = strip_tags($val);
			}
		}
	}
	
	/**
	 * verify authentication before perform
	 * @return bool
	 */
	function verifyAuth()
	{
		sanitizeRequest($_POST);
		sanitizeRequest($_GET);
		
		$currentUser = UserService::getInstance()->getCurrentUser();
		if (!$currentUser || !$currentUser->getSessionId()) {
			return false;
		}
		
		try {
			$payload = JWT::decode($currentUser->getSessionId(), SERVER_KEY, array('HS256'));
			$sessionId = UserService::getInstance()->getSessionId($currentUser->getId());
			if ($sessionId === $currentUser->getSessionId() &&
					$currentUser->getId() === $payload->id &&
					$currentUser->getEmail() === $payload->email) {
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
			if ($currentUser->isRemember() && $e->getMessage() === 'Expired token') {
				$currentUser->extendSession();
				return true;
			}
			return false;
		}
		return false;
	}
	
	/**
	 * deny access
	 */
	function accessDenied()
	{
		session_unset();
		session_destroy();
		NotificationService::getInstance()->errorThenReload('Your session has expired');
		exit();
	}
	
	/**
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	
	/**
	 * @param $encodeString
	 * @return mixed
	 */
	function decode64($encodeString)
	{
		$decodeStringId = base64_decode($encodeString);
		if (strpos($decodeStringId, SECRET_KEY)) {
			return str_replace(SECRET_KEY, '', $decodeStringId);
		} else {
			return null;
		}
	}
	
	/**
	 * @param $decodeString
	 * @return string
	 */
	function encode64($decodeString)
	{
		return base64_encode($decodeString . SECRET_KEY);
	}
	
	/**
	 * @param $string
	 * @return string
	 */
	function truncate($string)
	{
		if (strlen($string) > 25) {
			return substr($string, 0, 25) . '...';
		}
		return $string;
	}
	
	/**
	 * @param $sqlResults
	 * @return array
	 */
	function toArray($sqlResults)
	{
		$newArray = array();
		foreach ($sqlResults as $sqlResult) {
			$obj = array();
			foreach ($sqlResult as $key => $val) {
				$obj[$key] = $val;
			}
			array_push($newArray, $obj);
		}
		return $newArray;
	}
	
	/**
	 * @param $req
	 * @param $key
	 * @return bool
	 */
	function hasValueDropdown($req, $key)
	{
		return isset($req[$key]) && $req[$key] !== '-1';
	}
	
	/**
	 * @param $arr1
	 * @param $arr2
	 * @return array
	 */
	function concat2Array($arr1, $arr2)
	{
		$resArr = array();
		foreach ($arr1 as $arr) {
			$obj = array();
			foreach ($arr as $key => $val) {
				$obj[$key] = $val;
			}
			array_push($resArr, $obj);
		}
		foreach ($arr2 as $arr) {
			$obj = array();
			foreach ($arr as $key => $val) {
				$obj[$key] = $val;
			}
			array_push($resArr, $obj);
		}
		return $resArr;
	}
	
	/**
	 * @param $data
	 * @return array
	 */
	function processDefaultLocation($data)
	{
		$processData = array();
		$processMapData = array();
		$tempArray = array();
		$tempMapArray = array();
		foreach ($data as $res) {
			if (!in_array($res['rootName'], $processData)) {
				array_push($processData, $res['rootName']);
				array_push($processMapData, $res['rootId'] . '/' . '0' . ':' . $res['rootName']);
			}
			if ($res['containerParentId'] === 0) {
				$containerPath = $res['rootName'] . '/' . $res['containerName'];
				$containerIdPath = $res['rootId'] . '/' . $res['containerId'];
				$tempArray[$res['containerId']] = $containerPath;
				$tempMapArray[$res['containerId']] = $containerIdPath;
				array_push($processData, $containerPath);
				array_push($processMapData, $containerIdPath . ':' . $containerPath);
			} else {
				$containerPath = $tempArray[$res['containerParentId']] . '/' . $res['containerName'];
				$containerIdPath = $tempMapArray[$res['containerParentId']] . '/' . $res['containerId'];
				$tempArray[$res['containerId']] = $tempArray[$res['containerParentId']] . '/' . $res['containerName'];
				$tempMapArray[$res['containerId']] = $tempMapArray[$res['containerParentId']] . '/' . $res['containerId'];
				array_push($processData, $containerPath);
				array_push($processMapData, $containerIdPath . ':' . $containerPath);
			}
		}
		$allRootNames = FormService::getInstance()->getDocumentRootNames();
		foreach ($allRootNames as $allRootName) {
			if (!in_array($allRootName['name'], $processData)) {
				array_push($processData, $allRootName['name']);
				array_push($processMapData, $allRootName['id'] . '/' . '0' . ':' . $allRootName['name']);
			}
		}
		return array($processData, $processMapData);
	}
	
	/**
	 * @param $data
	 * @return string
	 */
	function parseFullLocationToPairId($data)
	{
		$containerPaths = FormService::getInstance()->getAllContainerPath();
		if ($data['save_mode'] !== FORM_MANUAL) {
			$processedMapData = processDefaultLocation($containerPaths)[1];
			$mappedId = '';
			foreach ($processedMapData as $mapIdPath) {
				$arrMap = explode(":", $mapIdPath);
				if ($data['default_location'] === $arrMap[1]) {
					$mappedId = $arrMap[0];
					break;
				}
			}
			if (strlen($mappedId) > 0) {
				$containerId = substr($mappedId, 0, strpos($mappedId, '/'));
				$subContainerId = substr($mappedId, strrpos($mappedId, '/') + 1, strlen($mappedId));
				return $containerId . ':' . $subContainerId;
			}
		}
		return '';
	}
	
	/**
	 * @param $first_name
	 * @param $last_name
	 * @param $username
	 * @return string
	 */
	function getDisplayName($first_name, $last_name, $username)
	{
		$resStr = '';
		if (!empty($first_name) && !empty($last_name)) {
			$resStr = $first_name . ' ' . ucwords($last_name[0]) . '.';
		}
		if (empty($resStr)) {
			$resStr = $username;
		}
		return $resStr;
	}
	
	/**
	 * @param $currentUser
	 * @return bool
	 */
	function isAllUserRoles($currentUser)
	{
		return
				$currentUser->isAdmin() ||
				$currentUser->isConsultant() ||
				$currentUser->isUser() ||
				$currentUser->isUserManager() ||
				$currentUser->isGhostUser();
	}
	
	/**
	 * @param $currentUser
	 * @return bool
	 */
	function isManageUserRoles($currentUser)
	{
		return
				$currentUser->isAdmin() ||
				$currentUser->isConsultant() ||
				$currentUser->isUserManager();
	}
	
	/**
	 * @param string $msg
	 */
	function exitOnNoPermission($msg = MSG_DONT_HAVE_PERMISSION)
	{
		print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => $msg)));
		exit();
	}
	
	/**
	 * @return mixed
	 */
	function getActionName()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST[ACTION_KEY])) {
			return $_POST[ACTION_KEY];
		} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET[ACTION_KEY])) {
			return $_GET[ACTION_KEY];
		} else {
			print_r(json_encode(array('result' => RESULT_FAIL, 'msg' => 'Forbidden to operate this method')));
			exit();
		}
	}
	
	/**
	 * @param $result
	 * @param $message
	 * @param $data
	 */
	function sendResponse($result, $message, $data = null)
	{
		if ($data === null) {
			print_r(json_encode(array('result' => $result, 'msg' => $message)));
		} else {
			print_r(json_encode(array('result' => $result, 'msg' => $message, 'data' => $data)));
		}
	}