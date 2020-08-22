<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-05-11
	 * Time: 19:35
	 */
	session_start();
	require_once '../utils/utils.php';
	
	function render()
	{
		require_once '404.view.php';
	}
	
	if (verifyAuth()) {
		render();
	} else {
		accessDenied();
	}