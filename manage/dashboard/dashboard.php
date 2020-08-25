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
	require_once '../../services/MailerService.php';
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
	 * handle client request
	 */
	if (verifyAuth()) {
		$currentUser = UserService::getInstance()->getCurrentUser();
		$action = getActionName();
		if ($action === 'renderPage') {
			render();
		} else if ($action === 'add_student'){
			$data = array(
				'id' => isset($_POST['id']) ? $_POST['id'] : '',
				'gender' => $_POST['gender'],
				'religion' => $_POST['religion'],
				'nationality' => $_POST['nationality'],
				'residence_country' => $_POST['residence_country'],
				'data' => $_POST['data'],
				'residence_city' => $_POST['residence_city'],
				'cell_number' => $_POST['cell_number'],
				'email' => $_POST['email'],
				'computer' => $_POST['computer'],
				'english' => $_POST['english'],
				'last_degree' => $_POST['last_degree'],
				'education_level' => $_POST['education_level'],
				'specialization' => $_POST['specialization'],
				'inst_level_name' => $_POST['inst_level_name'],
				'inst_name' => $_POST['inst_name'],
				'grade_name' => $_POST['grade_name'],
				'course' => $_POST['course'],
				'remarks_by_akeb' => $_POST['remarks_by_akeb'],
				'academic_year' => $_POST['academic_year'],
				'mode' => $_POST['mode']);
			if ($data['mode'] === 'update'){
				MailerService::getInstance()->send('User <b>' . $data['email'] . ' has been updated</b>');
			}
			UserService::getInstance()->addStudent($data);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'getStudents'){
			$students = UserService::getInstance()->getStudents();
			sendResponse(RESULT_OK, MSG_SUCCESS, toArray($students));
		} else if ($action === 'removeStudent') {
			$student_id = $_POST['id'];
			UserService::getInstance()->removeStudent($student_id);
			sendResponse(RESULT_OK, MSG_SUCCESS);
		} else if ($action === 'getStudent'){
			$student_id = $_GET['id'];
			$result = UserService::getInstance()->getStudentById($student_id);
			sendResponse(RESULT_OK, MSG_SUCCESS, toArray($result));
		}
	} else {
		accessDenied();
	}


