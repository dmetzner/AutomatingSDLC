/* eslint-env jquery */
/* global Swal */

// eslint-disable-next-line no-unused-vars
function Follower (unfollowUrl, followUrl, somethingWentWrongError, followError, unfollowError,
  visibleFollowing = 5, visibleFollowers = 5, showSte = 5,
  minAmountOfVisibleFollowers = 5, totalFollowing = 5, totalFollowers = 5) {
  const self = this
  self.unfollowUrl = unfollowUrl
  self.followUrl = followUrl
  self.somethingWentWrongError = somethingWentWrongError
  self.followError = followError
  self.unfollowError = unfollowError

  self.unfollow = function (id, username) {
    const $followerItem = $('.follower-item-' + id)
    const $buttons = $followerItem.find('.follow-button button').attr('disabled', true)

    Swal.fire({
      title: 'Are you sure you want to unfollow ' + username,
      text: self.notificationDeleteAllMessage,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#00acc1',
      confirmButtonText: 'Unfollow ' + username,
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: self.unfollowUrl + '/' + id,
          type: 'get',
          success: function () {
            $buttons.attr('disabled', false)
            totalFollowing--
            reloadSources()
          },
          error: function (xhr) {
            handleError(xhr, $buttons)
          }
        })
        toggleEmptyText()
      } else {
        $buttons.attr('disabled', false)
      }
    })
  }

  self.follow = function (id) {
    const $followerItem = $('.follower-item-' + id)
    const $buttons = $followerItem.find('.follow-button button').attr('disabled', true)

    $.ajax({
      url: self.followUrl + '/' + id,
      type: 'get',
      success: function () {
        $buttons.attr('disabled', false)
        totalFollowing++
        reloadSources()
      },
      error: function (xhr) {
        handleError(xhr, $buttons)
      }
    })
    toggleEmptyText()
  }

  function toggleEmptyText () {
    if (totalFollowing > 0) {
      $('#no-following').removeClass('d-block').addClass('d-none')
    } else {
      $('#no-following').removeClass('d-none').addClass('d-block')
    }
  }

  function reloadSources () {
    $('#following-cards').load(window.location.href + ' #following-cards>*')
    $('#follower-cards').load(window.location.href + ' #follower-cards>*')
    $('#user-information').load(window.location.href + ' #user-information>*')
    $('#new-notifications-container').load(window.location.href + ' #new-notifications-container>*', '')
    $('#old-notifications-container').load(window.location.href + ' #old-notifications-container>*', '')
  }

  function handleError (xhr, $buttons) {
    if (xhr.status === 401) {
      // a user must be logged in to (un)follow someone
      window.location.replace('fos_user_security_login')
      return
    }
    if (xhr.status === 422) {
      // can't (un)follow yourself, or a user that does not exist
      window.location.replace('profile')
      return
    }
    $buttons.attr('disabled', false)
    Swal.fire(somethingWentWrongError, followError, 'error')
  }
}
