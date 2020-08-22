<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-03-15
	 * Time: 22:41
	 */
	session_start();
	
	require_once '../utils/utils.php';
	require_once '../../services/UserService.php';
	require_once '../../assets/vendors/custom/moment/vendor/autoload.php';
	
	\Moment\Moment::setDefaultTimezone('UTC');
	
	/**
	 * render current page
	 */
	function render()
	{
		$currentUser = UserService::getInstance()->getCurrentUser();
		require_once 'dashboard.view.php';
	}
	
	/**
	 * @param $startDate
	 * @param $endDate
	 * @return string
	 * @throws \Moment\MomentException
	 */
	function getTimeDirection($startDate, $endDate)
	{
		$end_date = (new \Moment\Moment($startDate))->addYears($endDate);
		$m = new \Moment\Moment($end_date->format(TIME_FORMAT));
		return $m->fromNow()->getDirection();
	}
	
	/**
	 * handle client request
	 */
	if (verifyAuth()) {
		$currentUser = UserService::getInstance()->getCurrentUser();
		$action = getActionName();
		if ($action === 'renderPage') {
			render();
		}
	} else {
		accessDenied();
	}


