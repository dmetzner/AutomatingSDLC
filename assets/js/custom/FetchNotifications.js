function FetchNotifications (countNotificationsUrl, maxAmountToFetch, refreshRate) {
  const self = this
  self.countNotificationsUrl = countNotificationsUrl
  self.maxAmountToFetch = maxAmountToFetch
  self.refreshRate = refreshRate

  self.run = function (fetch_type) {
    $.ajax({
      url: self.countNotificationsUrl,
      type: 'get',
      success: function (data) {
        for (const notification_type in data.count) {
          const userNotificationBadge = $('.' + notification_type)
          const numOfNotifications = data.count[notification_type]
          if (numOfNotifications > 0) {
            const text = (numOfNotifications <= self.maxAmountToFetch)
              ? numOfNotifications.toString() : (self.maxAmountToFetch + '+')
            userNotificationBadge.text(text)
            userNotificationBadge.show()
          } else {
            userNotificationBadge.text('')
            userNotificationBadge.hide()
          }
        }
        if (fetch_type != 'markAsRead') {
          setTimeout(self.run, refreshRate)
        }
      },
      error: function () {
        console.error('Unable to fetch user notifications!')
      }
    })
  }
}
