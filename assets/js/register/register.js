
let termsModalAgreeButton = ''
let termsModalAgreed = false

$(document).ready(function () {
  //
  // 2 different cases to register an account:
  //
  // - default fos user account registration
  // - Google Sign In
  //
  $(document).on('click', '#agreeButton', function () {
    termsModalAgreed = true
    switch (termsModalAgreeButton) {
      case 'registration_form':
        $('#registration_form').submit()
        termsModalAgreeButton = ''
        break
      case 'google_login':
        triggerGoogleLogin()
        termsModalAgreeButton = ''
        break
    }
  })
  // Default registration
  $('#registration_form').submit(function () {
    termsModalAgreeButton = 'registration_form'
    return termsModalAgreed // only submit after agreed terms of modal
  })
  
  // Google Sign in
  $(document).on('click', '#btn-login_google', function () {
    termsModalAgreeButton = 'google_login'
  })
})

function openDialog(defaultText = chooseUserNameDialogText, defaultInputValue = '') {
  //
  // Choose username SweetAlert2 Dialog:
  //
  // Gets called when a user sign in via OAuth, but has no account already registered.
  //
  Swal.fire({
    title: chooseUserNameDialogTitleText,
    text: defaultText,
    input: 'text',
    inputValue: defaultInputValue,
    showCancelButton: false,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: okButtonText,
    allowOutsideClick: false,
    inputValidator: (value) => {
      if (!value || value.length < 3 || value.length > 180) {
        return usernameInvalidSize
      }
    }
  }).then((result) => {

      let username = result.value
      let $url = Routing.generate('catrobat_oauth_login_username_available', { flavor: flavor })
      $.post($url,
        {
          username: username
        },
        function (data) {
          if (data['username_available'] === true)
          {
            // The user has to choose a valid username
            return openDialog(usernameTaken, username)
          }
          // Register the user with google
          let fbOrGoogle = $('#fb_google').val()
          if (fbOrGoogle === 'g+')
          {
            sendCodeToServer(
              $('#access_token_oauth').val(),
              $('#id_oauth').val(),
              username,
              $('#email_oauth').val(),
              $('#locale_oauth').val())
          }
        }
      )
  })
}
