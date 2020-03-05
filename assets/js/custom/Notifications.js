function Notification (new_notifications, old_notifications, markAsReadUrl, markAllAsReadUrl,
  deleteAllUrl, deleteNotificationUrl,
  popUpClearedAllMessagesTitle, popUpClearedAllMessagesText,
  somethingWentWrongError, notificationsClearError, notificationDeleteMessage,
  notificationDeleteAllMessage, confirmMessage, cancelMessage,
  deleteNotificationConfirmation, notificationDeletedMessage,
  notificationsAllMessagesDeleted, notificationsDeleteError,
  countNotificationsUrl, notification_type) {
  const self = this
  self.notifications = parseInt(new_notifications)
  self.old_notifications = parseInt(old_notifications)
  self.markAsReadUrl = markAsReadUrl
  self.deleteNotificationUrl = deleteNotificationUrl
  self.popUpClearedAllMessagesTitle = popUpClearedAllMessagesTitle
  self.popUpClearedAllMessagesText = popUpClearedAllMessagesText
  self.somethingWentWrongError = somethingWentWrongError
  self.notificationsClearError = notificationsClearError
  self.notificationDeleteMessage = notificationDeleteMessage
  self.notificationDeleteAllMessage = notificationDeleteAllMessage
  self.confirmMessage = confirmMessage
  self.cancelMessage = cancelMessage
  self.deleteNotificationConfirmation = deleteNotificationConfirmation
  self.notificationDeletedMessage = notificationDeletedMessage
  self.notificationsAllMessagesDeleted = notificationsAllMessagesDeleted
  self.countNotificationsUrl = countNotificationsUrl

  self.init = function () {
    const markAllAsSeenButton = $('#mark-all-as-seen')
    const deleteAllButton = $('#delete-all')

    if (self.notifications === 0 && self.old_notifications === 0) {
      markAllAsSeenButton.hide()
      deleteAllButton.hide()
      $('.no-notifications-placeholder').show()
      $('#notifications-summary').hide()
    } else if (self.notifications === 0) {
      markAllAsSeenButton.hide()
      $('.no-notifications-placeholder').hide()
    } else {
      $('.no-notifications-placeholder').hide()
    }
    if (self.old_notifications === 0) {
      $('#old-notification-header').hide()
    }

    markAllAsSeenButton.click(self.markAllNotificationAsRead)
    deleteAllButton.click(self.deleteAllNotifications)

    $('.notification-link').click(function () {
      const programLayer = $(this).parent('.program')
      const parentProgramsLayer = programLayer.parent('.programs')
      const remixedProgramsLayer = parentProgramsLayer.parent('.remixed-programs')
      programLayer.remove()

      if (parentProgramsLayer.children().length === 0) {
        remixedProgramsLayer.remove()
        if ($('#notifications').children().length === 0) {
          $('#mark-all-as-seen').hide()
          $('.no-notifications-placeholder').show()
        }
      }
      window.location = $(this).attr('href')
      return true
    })
  }

  self.markAllNotificationAsRead = function () {
    $.ajax({
      url: markAllAsReadUrl,
      type: 'get',
      success: function (data) {
        if (!data.success) {
          Swal.fire(somethingWentWrongError, notificationsClearError, 'error')
          return
        }

        self.old_notifications = self.notifications
        self.notifications = 0

        $('#new-notifications-container').children().appendTo('#old-notifications-container')

        self.updateBadgeNumber('specificNotification')
        const mark_all_as_read = 'markAllAsRead'
        self.showAllClearedPopUp()
        self.manageDisplayedElements(mark_all_as_read)
      },
      error: function () {
        Swal.fire(somethingWentWrongError, notificationsClearError, 'error')
      }
    })
  }

  self.deleteAllNotifications = function () {
    Swal.fire({
      title: self.deleteNotificationConfirmation,
      text: self.notificationDeleteAllMessage,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: self.confirmMessage,
      cancelButtonText: self.cancelMessage
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: deleteAllUrl,
          type: 'get',
          success: function (data) {
            if (!data.success) {
              Swal.fire(somethingWentWrongError, notificationsClearError, 'error')
              return
            }
            self.notifications = 0
            self.old_notifications = 0

            self.updateBadgeNumber('specificNotification')
            self.showAllClearedPopUp('delete_all')
            const delete_all = 'deleteAll'
            self.manageDisplayedElements(delete_all)
          },
          error: function () {
            Swal.fire(somethingWentWrongError, notificationsDeleteError, 'error')
          }
        })
      }
    })
  }

  self.markAsRead = function (id) {
    $.ajax({
      url: self.markAsReadUrl + '/' + id,
      type: 'get',
      success: function (data) {
        if (data.success) {
          self.notifications--
          self.old_notifications++

          self.updateNotificationAmountText()

          self.updateBadgeNumber()

          $('#catro-notification-' + id).fadeOut('fast', function () {
            $('#old-notification-header').show().fadeIn('fast')
            $('#catro-notification-' + id).parent().remove()

            const notificationsContainer = $('#new-notifications-container')
            if (notificationsContainer.children().length === 0) {
              self.clearAll('mark_as_read')
              self.showAllClearedPopUp('mark_as_read')
            }

            $('#mark-as-read-' + id).hide()
          })
          const current_notification = document.getElementById('catro-notification-' + id)

          $('#old-notifications-container').prepend('<div class="col-md-12">' +
            current_notification.outerHTML + '</div>').hide().fadeIn('slow')
        } else {
          Swal.fire(somethingWentWrongError, notificationsClearError, 'error')
        }
      },
      error: function () {
        Swal.fire(somethingWentWrongError, notificationsClearError, 'error')
      }
    })
  }

  self.updateBadgeNumber = function (specific_notification) {
    if (notification_type === 'allNotifications') {
      const fetchNotifications = new FetchNotifications(countNotificationsUrl, 99, 10000)
      fetchNotifications.run('markAsRead')
    } else {
      const userNotificationBadge = $('.' + notification_type)
      const userNotificationBadge_all = $('.all-notifications')
      const userNotificationBadge_dropdown = $('.all-notifications-dropdown')
      if (specific_notification === 'specificNotification') {
        self.updateBadgeNumberCurrentType(userNotificationBadge_all, specific_notification)
        self.updateBadgeNumberCurrentType(userNotificationBadge_dropdown, specific_notification)
        userNotificationBadge.hide()
      } else {
        self.updateBadgeNumberCurrentType(userNotificationBadge_all, specific_notification)
        self.updateBadgeNumberCurrentType(userNotificationBadge, specific_notification)
        self.updateBadgeNumberCurrentType(userNotificationBadge_dropdown, specific_notification)
      }
    }
  }

  self.updateBadgeNumberCurrentType = function (userNotificationBadge, specific_notification) {
    const current_number = Number(userNotificationBadge.text())
    if (specific_notification !== 'specificNotification') {
      if (current_number > 1) {
        userNotificationBadge.text(current_number - 1)
      } else {
        userNotificationBadge.hide()
      }
    } else {
      const userNotificationBadgeSpecific = $('.' + notification_type)
      userNotificationBadgeSpecific.load(location.href)
      let current_number_specific = Number(userNotificationBadgeSpecific.text())

      if (Number.isNaN(current_number_specific)) {
        current_number_specific = 0
      }

      if (current_number - current_number_specific > 0) {
        userNotificationBadge.text(current_number - current_number_specific)
      } else {
        userNotificationBadge.hide()
      }
    }
  }

  self.updateNotificationAmountText = function () {
    const translations = []
    translations.push({
      key: '%amount%',
      value: self.notifications
    })
    const url = Routing.generate('translate_choice', {
      word: 'catro-notifications.summary',
      count: self.notifications,
      array: JSON.stringify(translations),
      domain: 'catroweb'
    })
    $.get(url, function (data) {
      $('#notifications-summary').show()
      $('#total_amount_of_notifications').text(data)
    })
  }

  self.clearAll = function (type) {
    if (type !== 'mark_as_read' && type !== 'delete') {
      $('#notifications-summary').hide()
      $('.no-notifications-placeholder').show()
    }
    $('#notifications').children().remove()
    $('#new-notifications-container').children().remove()
    $('#mark-all-as-seen').hide()
  }

  self.showAllClearedPopUp = function (type) {
    let message = self.popUpClearedAllMessagesText
    if (type === 'delete_all') {
      message = self.notificationsAllMessagesDeleted
    } else if (type === 'delete_notification') {
      message = self.notificationDeletedMessage
    }

    Swal.fire(
      {
        title: self.popUpClearedAllMessagesTitle,
        text: message,
        icon: 'success',
        confirmButtonClass: 'btn btn-success'
      }
    )
  }

  self.deleteNotification = function (id) {
    Swal.fire({
      title: self.deleteNotificationConfirmation,
      text: self.notificationDeleteMessage,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: self.confirmMessage,
      cancelButtonText: self.cancelMessage
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: self.deleteNotificationUrl + '/' + id,
          type: 'get',
          success: function (data) {
            if (data.success) {
              if ($('#new-notifications-container').children().find('#catro-notification-' + id).length) {
                self.notifications--

                self.updateNotificationAmountText()

                self.updateBadgeNumber()
              }

              $('#catro-notification-' + id).fadeOut(function () {
                const notificationsContainer = $('#new-notifications-container')
                const oldNotificationsContainer = $('#old-notifications-container')

                $('#catro-notification-' + id).parent().remove()

                if (notificationsContainer.children().length === 0) {
                  if (oldNotificationsContainer.children().length === 0) {
                    $('#old-notification-header').hide()
                    self.showAllClearedPopUp('delete_all')
                    self.clearAll()
                  }
                  self.clearAll('delete')
                }
                if (oldNotificationsContainer.children().length === 0) {
                  $('#old-notification-header').hide()
                }
              })
            } else {
              Swal.fire(somethingWentWrongError, notificationsDeleteError, 'error')
            }
          },
          error: function () {
            Swal.fire(somethingWentWrongError, notificationsDeleteError, 'error')
          }
        })
      }
    })
  }

  self.manageDisplayedElements = function (type) {
    if (type === 'markAllAsRead') {
      $('#old-notification-header').show()
      $('#total_amount_of_notifications').load(location.href + ' #total_amount_of_notifications')
      $('#mark-all-as-seen').hide()
      $('#mark-as-read .btn.btn-primary').hide()
    } else if (type === 'deleteAll') {
      $('#notifications').children().remove()
      $('#new-notifications-container').children().remove()
      $('#old-notifications-container').children().remove()
      $('#notifications-summary').hide()
      $('#mark-all-as-seen').hide()
      $('.no-notifications-placeholder').show()
      $('#old-notification-header').hide()
    }
  }
}
