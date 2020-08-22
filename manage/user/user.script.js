$(document).ready(function () {
  const user_id = ''
  const associateClient = getElmByIdJQuery('listAssociatedClient').DataTable({
    ...tableOption,
    paging: false,
    searching: false,
    info: false,
  })

  const user_table = getElmByIdJQuery('user_table').DataTable({
    ...tableOption,
    autoWidth: false,
    data: [],
    columnDefs: [
      { targets: [0, 1, 8], orderable: false },
      { targets: [9], visible: false },
      { orderData: [9], targets: [6] },
    ],
    columns: [
      { data: 'index' },
      { data: 'iconElm' },
      { data: 'email' },
      { data: 'firstNameElm' },
      { data: 'lastNameElm' },
      { data: 'roleElm' },
      { data: 'lastLoggedIn' },
      { data: 'status' },
      { data: 'actionElm' },
      { data: 'last_logged_in' },
    ],
    language: {
      emptyTable: 'No user',
    },
  })
  //handle No. column after sort
  user_table
    .on('order.dt search.dt', function () {
      user_table
        .column(0, { search: 'applied', order: 'applied' })
        .nodes()
        .each(function (cell, i) {
          cell.innerHTML = i + 1
        })
    })
    .draw()

  setRouteVariable(USER_ROUTE, { associateClient, user_table, user_id })
  initQuickPanel()
  loadAllUser(false)
  const currentUser = getRouteVariable(COMMON, 'currentUser')
  setElementVisible('btnAddNewUser', currentUser && currentUser.role === ADMIN)
})

/**
 *
 * @param method
 * @returns {{method: *, url: string}}
 */
function getRequestOpt(method) {
  return {
    url: 'new-user/user.php',
    method,
  }
}

/**
 *
 * @param softUser
 */
function loadAllUser(softUser) {
  const form = buildMessageForm({
    load_soft_user: softUser,
    action: 'getAllUsers',
  })
  sendRequest(form, getRequestOpt(GET), (response) => {
    if (isSuccess(response)) {
      const user_table = getRouteVariable('user', 'user_table')
      const processedData = response.data.map((user, idx) => {
        const {
          id,
          first_name,
          last_name,
          email,
          role,
          last_logged_in,
          deleted,
          current_user_role,
        } = user
        const iconElm =
          role === SOFT_USER
            ? '<i class="la la-lock kt-font-danger" style="font-size: x-large;" data-toggle="kt-tooltip" title="Soft user does not allow to login"></i>'
            : '<i class="flaticon-user kt-font-success" style="font-size: x-large;" data-toggle="kt-tooltip" title="User allows to login"></i>'
        const statusElm =
          deleted === 0
            ? '<span class="kt-badge kt-badge--success kt-badge--inline" style="display: initial">Active</span>'
            : '<span class="kt-badge kt-badge--danger kt-badge--inline" style="display: initial;">Inactive</span>'
        const lastLoggedIn = last_logged_in
          ? moment.utc(last_logged_in).local().fromNow()
          : 'Not login'
        const firstNameElm = '<span>' + first_name + '</span>'
        const lastNameElm = '<span>' + last_name + '</span>'
        const roleElm = '<span class="capitalize">' + role + '</span>'
        const status = '<span>' + statusElm + '</span>'

        let actionElm = ''
        if ([ADMIN, CONSULTANT].includes(current_user_role)) {
          actionElm +=
            '<a class="dropdown-item" href="javascript:;" onclick="editUserProfile(\'' +
            encodeURIComponent(JSON.stringify(user)) +
            '\')"><i class="flaticon-edit button-icon"></i>Edit profile</a>'
          if ([USER, USER_MANAGER].includes(role)) {
            actionElm +=
              '<a class="dropdown-item" href="javascript:;" onclick="showClients(' +
              id +
              ')"><i class="flaticon-map button-icon"></i>View associated client</a>'
          }
          const currentUser = getRouteVariable(COMMON, 'currentUser')
          setElementVisible(
            'createUserFromClient',
            currentUser && currentUser.role === ADMIN
          )
          if (
            role !== SOFT_USER &&
            currentUser &&
            !currentUser.viewMode &&
            currentUser.id !== id &&
            current_user_role === ADMIN
          ) {
            actionElm +=
              '<a class="dropdown-item" href="javascript:;" onclick="loginAs(' +
              id +
              ')"><i class="flaticon-visible button-icon"></i>Login as</a>'
          }
          if (current_user_role === ADMIN) {
            actionElm +=
              '<a class="dropdown-item" href="javascript:;" onclick="removeUser(' +
              id +
              ')"><i class="flaticon-delete button-icon"></i>Remove</a>'
          }
        }
        actionElm = wrapMenuAction(actionElm)
        return {
          index: idx + 1,
          iconElm,
          email,
          firstNameElm,
          lastNameElm,
          roleElm,
          lastLoggedIn,
          status,
          actionElm,
          last_logged_in,
        }
      })

      reDrawTable(user_table, processedData)
      KTApp.initTooltips()
    } else {
      toastr.error(response.msg)
    }
  })
}

/**
 *
 * @param userId
 */
function removeUser(userId) {
  const form = buildMessageForm({
    user_id: userId,
    action: 'removeUser',
  })
  formConfirm(
    'Remove user',
    'Are you sure?',
    form,
    getRequestOpt(POST),
    (response) => {
      if (isSuccess(response)) {
        loadAllUser(false)
        toastr.success(response.msg)
      } else {
        toastr.error(response.msg)
      }
    }
  )
}

/**
 *
 * @param elm
 */
function saveUserProfile(elm) {
  const form = $(elm).closest('form')
  form.validate({
    rules: {
      first_name: {
        required: true,
      },
      last_name: {
        required: true,
      },
      role: {
        required: true,
      },
      status: {
        required: true,
      },
    },
  })

  if (!form.valid()) {
    return
  }

  triggerButton('btnCloseUpdateUserInfo')
  sendRequest(form, getRequestOpt(POST), (response) => {
    if (isSuccess(response)) {
      loadAllUser(false)
      toastr.success(response.msg)
    } else {
      toastr.error(response.msg)
    }
  })
}

/**
 *
 * @param response
 */
function updateUserCallback(response) {
  if (isSuccess(response)) {
    loadAllUser(false)
    toastr.success(response.msg)
  } else {
    toastr.error(response.msg)
  }
}

/**
 *
 * @param userId
 */
function editUserProfile(userInfo) {
  const userSrc = JSON.parse(decodeURIComponent(userInfo))
  const form = getElmById('profile_user_form')
  const avatarElm = getElmById('user_avatar')
  avatarElm.setAttribute('src', userSrc.avatar)
  toArray(form.elements).map((elm) => {
    if (elm.name !== 'action') {
      elm.value = userSrc[elm.name]
    }
  })
  addFieldToForm(form, 'user_id', userSrc.id)
  addFieldToForm(form, 'user_email', userSrc.email)
  const currentUser = getRouteVariable(COMMON, 'currentUser')
  setElementVisible(
    'user_section_edit_role',
    currentUser && currentUser.id !== userSrc.id
  )
  triggerButton('openUserProfileModal')
}

/**
 * show add user panel
 */
function showUserPanel() {
  const form = buildMessageForm({
    load_all: false,
    action: 'getGetAllClients',
  })
  sendRequest(
    form,
    {
      url: './new-client/client.php',
      method: 'GET',
    },
    (response) => {
      if (isSuccess(response)) {
        const clients = response.data.clients
        renderCombobox(
          'user_client',
          clients.map((client) => {
            const { id, company_name: name } = client
            return { id, name }
          })
        )
        triggerPanel('Add user', true)
      } else {
        toastr.error(response.msg)
      }
    }
  )
}

/**
 * reload page
 */
function reload() {
  toRoute('new-user/user.php')
}

/**
 *
 * @param tableId
 * @param response
 */
function reloadTable(tableId, response) {
  setElementVisible('assignUserDropdown', response.data.length <= 0)
  const associateClient = getRouteVariable('user', 'associateClient')
  const processedData = response.data.map((item) => [
    item['company_name'],
    item['short_name'],
    item['email'],
    item['address'],
    [
      '<button class="btn btn-primary" title="Go for detail" href="javascript:;" onclick="goDetail(' +
        item['id'] +
        ')">Detail</i></button>',
    ],
  ])
  reDrawTable(associateClient, processedData)
}

/**
 * show associated client of user
 * @param userId
 */
function showClients(userId) {
  updateRouteVariable(USER_ROUTE, 'user_id', userId)
  const form = buildMessageForm({
    userId,
    action: 'getAssociatedClients',
  })
  sendRequest(form, getRequestOpt(POST), function (response) {
    if (isSuccess(response)) {
      reloadTable('listAssociatedClient', response)
      triggerButton('showAssociatedClients')
    } else {
      toastr.error(response.msg)
    }
  })
}

/**
 *
 * @param client
 */
function setClient(clientId) {
  getElmByIdJQuery('assignUserClientBtn').attr('disabled', false)
  setElmValuePropByElmName('clientId', encode64(clientId))
}

/**
 * assign user to client
 */
function doAssign() {
  getElmByIdJQuery('assignUserClientBtn').attr('disabled', true)
  KTApp.block('#assingModalContent', {
    overlayColor: '#000000',
    type: 'spinner',
    state: 'brand',
    opacity: 0.1,
    size: 'lg',
  })

  const userId = getRouteVariable(USER_ROUTE, 'user_id')
  const form = buildMessageForm({
    userId,
    clientId: getClientId(),
    action: 'assignUserToClient',
  })
  sendRequest(form, getRequestOpt(POST), function (response) {
    if (isSuccess(response)) {
      toastr.success(response.msg)
      const form = buildMessageForm({
        userId,
        action: 'getAssociatedClients',
      })
      sendRequest(form, getRequestOpt(POST), function (response) {
        if (isSuccess(response)) {
          reloadTable('listAssociatedClient', response)
        }
        KTApp.unblock('#assingModalContent')
      })
    } else {
      toastr.error(response.msg)
      KTApp.unblock('#assingModalContent')
    }
  })
}

/**
 *
 * @param clientId
 */
function goDetail(clientId) {
  storeClientId(encode64(clientId))
  triggerButton('closeModalBtnAssociate')
  setTimeout(() => {
    toRoute('new-client/client.php', { clientId: encode64(clientId) })
  }, 200)
}

/**
 * reset client dropdown when adding user
 */
function resetClientDropdown() {
  setElmValuePropByElmName('user_client', '-1')
}
