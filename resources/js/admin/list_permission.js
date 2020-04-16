import $ from "jquery";
import axios from "axios";


const ListRole = {
  init: function () {

    $('.delete_user').on('click', function () {
      $('#name_role_modal').html($(this).attr('role'));
      $('#role_delete_id').val($(this).attr('id-role'));
    });

    $('.del_role_confirm').on('click', function () {
      const data = {
        id: $('#role_delete_id').val(),
      };
      ListRole.deleteUser(data.id);
      $('.btn-secondary').click();
      $('#role_' + data.id).remove();
    });
  },



  /**
   * Удаление Пользователя
   */
  deleteUser: function (idUser) {
    const apiUrl = '/delete_permission_group';
    const dataObj = {
      group_id: idUser
    };
    axios.post(apiUrl, dataObj).catch(function (error) {
      console.log(error);
    });

  },

};

$(document).ready(function () {
  ListRole.init();
});

