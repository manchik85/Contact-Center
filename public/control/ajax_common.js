var Ajax = {
  /**
   * Общий AJAX-метод
   * @param dataObj
   * @param apiUrl
   * @param callback
   */
  ajaxComm: function(dataObj, apiUrl, callback) {
    $.ajax({
      type: 'POST',
      url: apiUrl,
      data: dataObj
    }).done(function(data) {
      callback(data);
    });
  },


};
