<?php
	/**
	 * Created by PhpStorm.
	 * User: lebaphi
	 * Date: 2019-06-02
	 * Time: 23:29
	 */
	session_start();
	session_unset();
	session_destroy();
	header("Location:../../");