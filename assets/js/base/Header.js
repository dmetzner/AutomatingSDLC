/* eslint-env jquery */

let searchUrl = 'undefined_search_url'

// eslint-disable-next-line no-unused-vars
function setSearchUrl (url) {
  searchUrl = url
}

// eslint-disable-next-line no-unused-vars
function hideTopBars () {
  $('.mdc-top-app-bar__row').hide()
}

// eslint-disable-next-line no-unused-vars
function showTopBarDefault () {
  hideTopBars()
  $('#top-app-bar__default').show()
}

// eslint-disable-next-line no-unused-vars
function showTopBarDownload () {
  hideTopBars()
  $('#top-app-bar__media-library-download').show()
}

// eslint-disable-next-line no-unused-vars
function showTopBarOptions () {
  const container = $('#top-app-bar__options-container')
  container.show()
  container.focus()
}

// eslint-disable-next-line no-unused-vars
function showTopBarSearch () {
  hideTopBars()
  $('#top-app-bar__search').show()
  $('#top-app-bar__search-input').focus()
}

// eslint-disable-next-line no-unused-vars
function controlTopBarSearchClearButton () {
  if ($('#top-app-bar__search-input').val()) {
    $('#top-app-bar__btn-search-clear').show()
  } else {
    $('#top-app-bar__btn-search-clear').hide()
  }
}

// eslint-disable-next-line no-unused-vars
function clearTopBarSearch () {
  const inputField = $('#top-app-bar__search-input')
  inputField.val('')
  $('#top-app-bar__btn-search-clear').hide()
  inputField.focus()
}

$(document).on('click', function (e) {
  // hide options container when user clicks
  if (!$('#top-app-bar__btn-options').is(e.target)) {
    $('#top-app-bar__options-container').hide()
  }
})

$(document).ready(function () {
  $('#top-app-bar__search-form').on('submit', function (event) {
    event.preventDefault()
    const query = $('#top-app-bar__search-input').val()
    window.location.href = searchUrl + encodeURIComponent(query.trim())
  })
})
