import axios from 'axios';

const Access = {
  init: function () {

    $('.accessSelect').on('click', function () {
      Access.accessSelect($(this).attr('level'), $(this).attr('group'), $(this).prop('checked'));
    });
  },

  /**
   * Изменение доступа для группы пользователей
   */
  accessSelect: function (level, group, status) {
    const apiUrl = '/users_change_access';
    const dataObj = {
      level:level,
      group:group,
      status:status
    };

    axios.post(apiUrl, dataObj)
      .catch(function (error) {
      console.log(error);
    });

  },
};

$(function () {
  Access.init();
});
