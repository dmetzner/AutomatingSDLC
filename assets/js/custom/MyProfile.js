const MyProfile = function (profile_url, save_username,
  save_email_url, save_country_url, save_password_url,
  delete_url, delete_account_url,
  toggle_visibility_url, upload_url,
  statusCode_OK,
  statusCode_USERNAME_ALREADY_EXISTS,
  statusCode_USERNAME_MISSING,
  statusCode_USERNAME_INVALID,
  statusCode_USER_EMAIL_ALREADY_EXISTS,
  statusCode_USER_EMAIL_MISSING,
  statusCode_USER_EMAIL_INVALID,
  statusCode_USER_COUNTRY_INVALID,
  statusCode_USER_USERNAME_PASSWORD_EQUAL,
  statusCode_USER_PASSWORD_TOO_SHORT,
  statusCode_USER_PASSWORD_TOO_LONG,
  statusCode_USER_PASSWORD_NOT_EQUAL_PASSWORD2,
  statusCode_PASSWORD_INVALID,
  successText, checkMailText, passwordUpdatedText,
  programCanNotChangeVisibilityTitle,
  programCanNotChangeVisibilityText) {
  const self = this
  self.profile_url = profile_url
  self.profile_edit_url = profile_url
  self.save_username = save_username
  self.save_email_url = save_email_url
  self.save_country_url = save_country_url
  self.save_password_url = save_password_url
  self.delete_url = delete_url
  self.upload_url = upload_url
  self.toggle_visibility_url = toggle_visibility_url
  self.country = null
  self.regex_email = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  self.data_changed = false
  self.delete_account_url = delete_account_url
  const blueColor = '#3085d6'
  const redColor = '#d33'
  const passwordEditContainer = $('#password-edit-container')
  const usernameEditContainer = $('#username-edit-container')
  const usernameData = $('#username-wrapper > .profile-data')
  const emailEditContainer = $('#email-edit-container')
  const emailData = $('#email-wrapper > .profile-data')
  const countryEditContainer = $('#country-edit-container')
  const countryData = $('#country-wrapper > .profile-data')
  const accountSettingsContainer = $('#account-settings-container')
  const profileSections = [
    [passwordEditContainer, null],
    [emailEditContainer, emailData], [countryEditContainer, countryData],
    [accountSettingsContainer, null]
  ]

  $(function () {
    $('.edit-container').hide()
  })

  $('#edit-password-button').on('click', function () {
    toggleEditSection(passwordEditContainer)
  })

  $('#edit-email-button').on('click', function () {
    toggleEditSection(emailEditContainer, emailData)
  })

  $('#edit-username-button').on('click', function () {
    toggleEditSection(usernameEditContainer, usernameData)
  })

  $('#edit-country-button').on('click', function () {
    toggleEditSection(countryEditContainer, countryData)
  })

  $('#account-settings-button').on('click', function () {
    toggleEditSection(accountSettingsContainer)
  })

  function stringTranslate (programName, catalogEntry) {
    const translations = []
    translations.push({ key: '%programName%', value: programName })
    const translateString = Routing.generate('translate_word', {
      word: catalogEntry,
      array: JSON.stringify(translations),
      domain: 'catroweb'
    }, false)

    return translateString
  }

  function toggleEditSection (container, data = null) {
    const fadeTime = 250
    if (container.is(':visible')) {
      container.slideUp()
      if (data) {
        data.fadeIn(fadeTime)
      }
    } else {
      container.slideDown()
      if (data) {
        data.fadeOut(fadeTime)
      }
      profileSections.forEach(function (entry) {
        if (entry[0] !== container) {
          entry[0].slideUp()
          if (entry[1]) {
            entry[1].fadeIn(fadeTime)
          }
        }
      })
    }
  }

  self.deleteProgram = function (id) {
    const programName = $('#program-' + id).find('.program-name').text()
    const catalogEntry = 'programs.deleteConfirmation'
    const url = stringTranslate(programName, catalogEntry)
    $.get(url, function (data) {
      const split = data.split('\n')
      Swal.fire({
        title: split[0],
        html: split[1] + '<br><br>' + split[2],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: blueColor,
        cancelButtonColor: redColor,
        confirmButtonText: split[3],
        cancelButtonText: split[4]
      }).then((result) => {
        if (result.value) {
          window.location.href = self.delete_url + '/' + id
        }
      })
    })
  }

  self.toggleVisibility = function (id) {
    $.get(self.toggle_visibility_url + '/' + id, {}, function (data) {
      const visibilityLockId = $('#visibility-lock-' + id)
      const visibilityLockOpenId = $('#visibility-lock-open-' + id)
      const programName = $('#program-' + id).find('.program-name').text()
      const catalogEntry = 'programs.changeVisibility'
      const url = stringTranslate(programName, catalogEntry)

      if (data === 'true') {
        $.get(url, function (data) {
          const split = data.split('\n')
          if (visibilityLockId.is(':visible')) {
            Swal.fire({
              title: split[0],
              html: split[3],
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: blueColor,
              cancelButtonColor: redColor,
              confirmButtonText: split[4],
              cancelButtonText: split[6]
            }).then((result) => {
              if (result.value) {
                visibilityLockId.hide()
                visibilityLockOpenId.show()
              }
            })
          } else {
            Swal.fire({
              title: split[0],
              html: split[1] + '<br><br>' + split[2],
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: blueColor,
              cancelButtonColor: redColor,
              confirmButtonText: split[5],
              cancelButtonText: split[6]
            }).then((result) => {
              if (result.value) {
                visibilityLockId.show()
                visibilityLockOpenId.hide()
              }
            })
          }
        })
      } else if (data === 'false') {
        Swal.fire({
          title: programCanNotChangeVisibilityTitle,
          text: programCanNotChangeVisibilityText,
          icon: 'error',
          confirmButtonClass: 'btn btn-danger'
        })
      }
    })
  }

  $(document).on('click', '#delete-account-button', function () {
    const url = Routing.generate('translate_word', {
      word: 'programs.deleteAccountConfirmation'
    }, false)
    $.get(url, function (data) {
      const split = data.split('\n')
      Swal.fire({
        title: split[0],
        html: split[1] + '<br><br>' + split[2],
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: blueColor,
        cancelButtonColor: redColor,
        confirmButtonText: split[3],
        cancelButtonText: split[4]
      }).then((result) => {
        if (result.value) {
          $.post(self.delete_account_url, null, function (data) {
            switch (parseInt(data.statusCode)) {
              case statusCode_OK:
                window.location.href = '../../'
            }
          })
        }
      })
      $('.swal2-container.swal2-shown').css('background-color', 'rgba(255, 0, 0, 0.75)')// changes the color of the overlay
    })
  })

  $(document).on('click', '#save-email', function () {
    $(this).hide()
    $('#email-ajax').show()

    const email = $('#email')
    const additionalEmail = $('#additional-email')
    $('.error-message').addClass('d-none')

    const new_email = email.val()
    const additional_email = additionalEmail.val()
    $.post(self.save_email_url, {
      firstEmail: new_email,
      secondEmail: additional_email
    }, function (data) {
      switch (parseInt(data.statusCode)) {
        case statusCode_OK:
          Swal.fire({
            title: successText,
            text: checkMailText,
            icon: 'success',
            confirmButtonClass: 'btn btn-success'
          }).then(() => {
            window.location.href = self.profile_edit_url
          })
          break

        case statusCode_USER_EMAIL_ALREADY_EXISTS:
          if (parseInt(data.email) === 1) {
            $('.text-email1-exists').removeClass('d-none')
          }
          if (parseInt(data.email) === 2) {
            $('.text-email2-exists').removeClass('d-none')
          }
          break

        case statusCode_USER_EMAIL_MISSING:
          $('.text-email-missing').removeClass('d-none')
          break

        case statusCode_USER_EMAIL_INVALID:
          if (parseInt(data.email) === 1) {
            $('.text-email1-not-valid').removeClass('d-none')
          }
          if (parseInt(data.email) === 2) {
            $('.text-email2-not-valid').removeClass('d-none')
          }
          break

        default:
          window.location.href = self.profile_edit_url
      }
      $('#email-ajax').hide()
      $('#save-email').show()
    })
    self.data_changed = false
  })

  $(document).on('click', '#save-username', function () {
    $(this).hide()
    $('#username-ajax').show()

    const username = $('#username')
    $('.error-message').addClass('d-none')

    const new_username = username.val()

    $.post(self.save_username, {
      username: new_username
    }, function (data) {
      switch (parseInt(data.statusCode)) {
        case statusCode_USERNAME_ALREADY_EXISTS:
          $('.text-username-exists').removeClass('d-none')
          break

        case statusCode_USERNAME_MISSING:
          $('.text-username-missing').removeClass('d-none')
          break

        case statusCode_USERNAME_INVALID:
          $('.text-username-not-valid').removeClass('d-none')
          break

        default:
          window.location.href = self.profile_edit_url
      }
      $('#username-ajax').hide()
      $('#save-username').show()
    })
    self.data_changed = false
  })

  $(document).on('click', '#save-country', function () {
    $(this).hide()
    $('#country-ajax').show()
    const country = $('#select-country').find('select').val()
    $.post(self.save_country_url, {
      country: country
    }, function (data) {
      switch (parseInt(data.statusCode)) {
        case statusCode_USER_COUNTRY_INVALID:
          alert('invalid country')
          break

        default:
          window.location.href = self.profile_edit_url
          break
      }
      $('#country-ajax').hide()
      $('#save-country').show()
    })
  })

  $(document).on('click', '#save-password', function () {
    $(this).hide()
    $('#password-ajax').show()

    const password = $('#password')
    const repeatPassword = $('#repeat-password')
    $('.error-message').addClass('d-none')
    password.parent().removeClass('password-failed')
    repeatPassword.parent().removeClass('password-failed')
    const new_password = password.val()
    const old_password = $('#old-password').val()
    const repeat_password = repeatPassword.val()

    $.post(self.save_password_url, {
      oldPassword: old_password,
      newPassword: new_password,
      repeatPassword: repeat_password
    }, function (data) {
      switch (parseInt(data.statusCode)) {
        case statusCode_USER_USERNAME_PASSWORD_EQUAL:
          $('.text-password-isusername').removeClass('d-none')
          break

        case statusCode_USER_PASSWORD_TOO_SHORT:
          $('.text-password-tooshort').removeClass('d-none')
          break

        case statusCode_USER_PASSWORD_TOO_LONG:
          $('.text-password-toolong').removeClass('d-none')
          break

        case statusCode_USER_PASSWORD_NOT_EQUAL_PASSWORD2:
          $('.text-password-nomatch').removeClass('d-none')
          break

        case statusCode_PASSWORD_INVALID:
          $('.text-password-wrongpassword').removeClass('d-none')
          break

        default:
          Swal.fire({
            title: successText,
            text: passwordUpdatedText,
            icon: 'success',
            confirmButtonClass: 'btn btn-success'
          }).then(() => {
            window.location.href = self.profile_edit_url
          })
          break
      }
      $('#password-ajax').hide()
      $('#save-password').show()
    })
  })
}
