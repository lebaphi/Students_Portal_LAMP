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
	
  setRouteVariable(DASHBOARD_ROUTE, {
  })
})

/**
 *
 */
function reload() {
  toRoute('./new-dashboard/dashboard.php')
}


/**
 * open modal remove client container
 */
function addStudent() {
  const form = buildMessageForm({
    client_id: getClientId(),
    action: 'getAllCustomContainer',
  })
  sendRequest(
    form,
    {
      url: './new-dashboard/dashboard.php',
      method: 'GET',
    },
    (response) => {
      if (isSuccess(response)) {
        const list_custom_container = getRouteVariable(
          DASHBOARD_ROUTE,
          'list_custom_container'
        )
        const processedData = response.data.map((item) => {
          const { id, name, description, created_date, style } = item
          const checkboxElm =
            '<label class="kt-checkbox kt-checkbox--solid kt-checkbox--success kt-checkbox-override">\n' +
            '<input type="checkbox" name="selectCustomContainerCbx" style="' +
            style +
            '" value="' +
            id +
            "\" onclick=\"onChecked(['removeCustomContainerBtn'], 'selectCustomContainerCbx')\">\n" +
            '<span></span>\n' +
            '</label>'
          const createdDate = moment.utc(created_date).local().fromNow()
          return [
            checkboxElm,
            name,
            description,
            createdDate,
            'Client Container',
          ]
        })

        reDrawTable(list_custom_container, processedData)
      } else {
        toastr.error(response.msg)
      }
    }
  )
  triggerButton('removeCustomContainerModal')
}
