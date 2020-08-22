$(document).ready(function () {
  const studentTable = getElmByIdJQuery('student_table').DataTable(
    {
      ...tableOption,
      autoWidth: false,
      language: {
        emptyTable: 'No student',
      },
    }
  )
	
  setRouteVariable(DASHBOARD_ROUTE, {studentTable})
	loadStudents()
})

/**
 *
 */
function reload() {
  toRoute('./dashboard/dashboard.php')
}

function showAddStudentModal(){
	const form = getElmById('add_student_form')
	addFieldToForm(form, 'mode', 'add')
	triggerButton('openStudentModal')
}

/**
 * open modal remove client container
 */
function addStudent(elm) {
	const form = $(elm).closest('form')
	form.validate({
		rules: {
			gender: {
				required: true,
			},
			religion: {
				required: true,
			},
			nationality: {
				required: true,
			},
			residence_country: {
				required: true,
			},
			data: {
				required: true,
			},
			residence_city: {
				required: true,
			},
			cell_number: {
				required: true,
			},
			email: {
				required: true,
			},
			computer: {
				required: true,
			},
			english: {
				required: true,
			},
			last_degree: {
				required: true,
			},
			education_level: {
				required: true,
			},
			specialization: {
				required: true,
			},
			inst_level_name: {
				required: true,
			},
			inst_name: {
				required: true,
			},
			grade_name: {
				required: true,
			},
			course: {
				required: true,
			},
			remarks_by_akeb: {
				required: true,
			},
			academic_year: {
				required: true,
			},
		},
	})
	
	if (!form.valid()) {
		return
	}
	
	sendRequest(
		form,
		{
			url: './dashboard/dashboard.php',
			method: 'POST',
		},
		(response) => {
			if (isSuccess(response)) {
				triggerButton('btnCloseModal')
				loadStudents()
				toastr.success(response.msg)
			} else {
				toastr.error(response.msg)
			}
		}
	)
}


/**
 * edit student
 * @param studentId
 */
function edit(studentId){
	const form = buildMessageForm({
		id: studentId,
		action: 'getStudent',
	})
	sendRequest(form, {
		url: './dashboard/dashboard.php',
		method: 'GET',
	},
		(response) => {
		if (isSuccess(response)){
			const editForm = getElmById('add_student_form')
			const formData = response.data[0]
			Array.from(editForm.elements).forEach(elm => {
				if (elm.name === 'action') return
				elm.value = formData[elm.name]
			})
			addFieldToForm(editForm, 'id', formData['id'])
			addFieldToForm(editForm, 'mode', 'update')
		} else {
			toastr.error(response.msg)
		}
	})
	triggerButton('openStudentModal')
}

/**
 * remove student
 * @param studentId
 */
function remove(studentId){
	formConfirm('Remove', 'Are you sure ?', null, null, (result) => {
		if (result) {
			const form = buildMessageForm({
				id: studentId,
				action: 'removeStudent',
			})
			sendRequest(form,
				{
					url: './dashboard/dashboard.php',
					method: 'POST',
				},
				(response) => {
					if (isSuccess(response)){
						loadStudents()
						toastr.success(response.msg)
					}else {
						toastr.error(response.msg)
					}
				})
		}
	});
}

/**
 * load all students
 */
function loadStudents(){
	const form = buildMessageForm({
		action: 'getStudents',
	})
	sendRequest(
		form,
		{
			url: './dashboard/dashboard.php',
			method: 'GET',
		},
		(response) => {
			if (isSuccess(response)) {
				const processedData = response.data.map((item) => {
					const { id, gender, email, nationality, last_degree, education_level, grade_name, course, academic_year } = item
					const actionElm = '' +
						'<a href="javascript:;" class="dropdown-item" onclick="edit(' + id + ')">' +
						'<i class="flaticon2-edit button-icon"></i>Edit' +
						'</a>' +
						'<a href="javascript:;" class="dropdown-item" onclick="remove(' + id + ')">' +
						'<i class="flaticon2-trash button-icon"></i>Remove' +
						'</a>';
					return ['', gender, email, nationality, last_degree, education_level, grade_name, course, academic_year, wrapMenuAction(actionElm)]
				})
				const table = getRouteVariable(DASHBOARD_ROUTE, 'studentTable')
				reDrawTable(table, processedData)
			} else {
				toastr.error(response.msg)
			}
		}
	)
}
