
import $ from 'jquery';
import axios from 'axios';

const List = {
    init: function () {

        $('.delete_user').on('click', function () {
            $('#name_user_modal').html($(this).attr('name-user'));
            $('#user_delete_id').val($(this).attr('id-user'));
        });
        $('.bann_user').on('click', function () {
            $('#name_user_modal_bann').html($(this).attr('name-user'));
            $('#user_bann_id').val($(this).attr('id-user'));
        });

        $('.del_user_confirm').on('click', function () {
            const data = {
                id: $('#user_delete_id').val(),
            };
            List.deleteUser(data.id);
            $('.btn-secondary').click();
        });

        $('.bann_user_confirm').on('click', function () {
            const data = {
                id: $('#user_bann_id').val(),
            };
            List.banUser(data.id);
            $('.btn-secondary').click();
        });


        $('.users_profile').on('click', function () {
            $('#prifileUserId').val($(this).attr('idUser'));
            $('#admChenUserPage').submit();
        });

        $('.statistic_user').on('click', function () {
            $('#statUserId').val($(this).attr('id-user'));
            $('#statPage').submit();
        });

    },

    /**
     * Блокируем Пользователя
     */
    banUser: function (idUser) {
        const apiUrl = '/users_ban';
        const dataObj = {
            users_id: idUser
        };
        axios.post(apiUrl, dataObj).then(function (response) {
              if(response.data.st>0){
                  $('#user_' + dataObj.users_id).remove();
              }
          })
          .catch(function (error) {
              console.log(error);
          });
    },

    /**
     * Удаление Пользователя
     */
    deleteUser: function (idUser) {
        const apiUrl = '/delete_user';
        const dataObj = {
            users_id: idUser
        };
        axios.post(apiUrl, dataObj).catch(function (error) {
              console.log(error);
          });
        $('#user_' + dataObj.users_id).remove();
    },

};

$(document).ready(function () {
    List.init();
});

