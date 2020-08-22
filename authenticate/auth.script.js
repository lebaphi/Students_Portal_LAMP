'use strict'

const KTLoginGeneral =  (function () {
  const login = $('#kt_login')

  const showMsg = function (form, type, msg) {
    const alert = $(
      '<div class="kt-alert kt-alert--outline alert alert-' +
        type +
        ' alert-dismissible" role="alert">\
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
			<span></span>\
		</div>'
    )

    form.find('.alert').remove()
    alert.prependTo(form)
    KTUtil.animateClass(alert[0], 'fadeIn animated')
    alert.find('span').html(msg)
  }

  const displaySignUpForm = function () {
    login.removeClass('kt-login--forgot')
    login.removeClass('kt-login--signin')

    login.addClass('kt-login--signup')
    KTUtil.animateClass(login.find('.kt-login__signup')[0], 'flipInX animated')
  }

  const displaySignInForm = function () {
    login.removeClass('kt-login--forgot')
    login.removeClass('kt-login--signup')

    login.addClass('kt-login--signin')
    KTUtil.animateClass(login.find('.kt-login__signin')[0], 'flipInX animated')
  }

  const displayForgotForm = function () {
    login.removeClass('kt-login--signin')
    login.removeClass('kt-login--signup')

    login.addClass('kt-login--forgot')
    KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated')
  }

  const setWaiting = function (btn, isWaiting) {
    if (isWaiting) {
      btn
        .addClass(
          'kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light'
        )
        .attr('disabled', isWaiting)
    } else {
      btn
        .removeClass(
          'kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light'
        )
        .attr('disabled', isWaiting)
    }
  }

  const setElmStatus = function (elm, disable) {
    elm.attr('disabled', disable)
  }

  const handleFormSwitch = function () {
    $('#kt_login_forgot').click(function (e) {
      e.preventDefault()
      displayForgotForm()
    })

    $('#kt_login_forgot_cancel').click(function (e) {
      e.preventDefault()
      displaySignInForm()
    })

    $('#kt_login_signup').click(function (e) {
      e.preventDefault()
      displaySignUpForm()
    })

    $('#kt_login_signup_cancel').click(function (e) {
      e.preventDefault()
      displaySignInForm()
    })
  }

  const handleSignInFormSubmit = function () {
    $('#kt_login_signin_submit').click(function (e) {
      e.preventDefault()
      const btn = $(this)
      const form = $(this).closest('form')

      form.validate({
        rules: {
          email: {
            required: true,
            email: true,
          },
          password: {
            required: true,
          },
        },
      })

      if (!form.valid()) {
        return
      }

      setWaiting(btn, true)

      form.ajaxSubmit({
        url: 'auth.php',
        type: 'POST',
        success: function (response) {
          try {
            response = JSON.parse(response)
            if (response.status === 200) {
              window.location.href += 'manage/'
            } else {
              setWaiting(btn, false)
              setElmStatus(btn, response.status === 403)
              showMsg(form, 'danger', response.msg)
            }
          } catch (err) {
            setWaiting(btn, false)
            showMsg(
              form,
              'danger',
              'We are currently experiencing some downtime, please try again later or contact support.'
            )
          }
        },
      })
    })
  }

  const handleSignUpFormSubmit = function () {
    $('#kt_login_signup_submit').click(function (e) {
      e.preventDefault()

      const btn = $(this)
      const form = $(this).closest('form')

      form.validate({
        rules: {
          username: {
            required: true,
          },
          email: {
            required: true,
            email: true,
          },
          password: {
            required: true,
          },
          rpassword: {
            required: true,
          },
          agree: {
            required: true,
          },
        },
      })

      if (!form.valid()) {
        return
      }

      setWaiting(btn, true)

      form.ajaxSubmit({
        url: 'auth.php',
        type: 'POST',
        success: function (response) {
          setWaiting(btn, false)
          try {
            response = JSON.parse(response)
            if (response.status === 200) {
              form.clearForm()
              form.validate().resetForm()
              displaySignInForm()
              const signInForm = login.find('.kt-login__signin form')
              signInForm.clearForm()
              signInForm.validate().resetForm()
              showMsg(signInForm, 'success', response.msg)
            } else {
              showMsg(form, 'danger', response.msg)
            }
          } catch (err) {
            showMsg(form, 'danger', err)
          }
        },
      })
    })
  }

  const handleForgotFormSubmit = function () {
    $('#kt_login_forgot_submit').click(function (e) {
      e.preventDefault()

      const btn = $(this)
      const form = $(this).closest('form')

      form.validate({
        rules: {
          email: {
            required: true,
            email: true,
          },
        },
      })

      if (!form.valid()) {
        return
      }

      setWaiting(btn, true)

      form.ajaxSubmit({
        url: 'auth.php',
        method: 'POST',
        success: function (response) {
          setWaiting(btn, false)
          try {
            response = JSON.parse(response)
            if (response.result === 'ok') {
              form.clearForm()
              form.validate().resetForm()

              displaySignInForm()
              const signInForm = login.find('.kt-login__signin form')
              signInForm.clearForm()
              signInForm.validate().resetForm()

              showMsg(
                signInForm,
                'success',
                'Cool! Password recovery instruction has been sent to your email.'
              )
            } else {
              showMsg(form, 'danger', response.msg)
            }
          } catch (err) {
            showMsg(form, 'danger', err)
          }
        },
      })
    })
  }

  // Public Functions
  return {
    // public functions
    init: function () {
      handleFormSwitch()
      handleSignInFormSubmit()
      handleSignUpFormSubmit()
      handleForgotFormSubmit()
    },
  }
})()

// Class Initialization
jQuery(document).ready(function () {
  KTLoginGeneral.init()
})
