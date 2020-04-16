
// import axios from "axios";
//
// window.Vue = require('vue');

// import VueTheMask from 'vue-the-mask';
// Vue.use(VueTheMask);

// import Inputmask from 'inputmask';
// Vue.use(Inputmask);

import axios from "axios";

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


// Vue.component('add-mail',   require('../components/admin/AddMailComponent.vue').default);
//
// const app = new Vue({
//   el: '#app'
// });




const ListMail = {
    init: function () {

      $('.delete_user').on('click', function () {
        $('#mailing_list_id').val($(this).attr('id-mail'));
      });

      $('.del_user_confirm').on('click', function () {
        const data = {
          id: $('#mailing_list_id').val(),
        };
        ListMail.deleteEmail(data);
        $('.btn-secondary').click();
      });


      $('.look').on('click', function () {
        $('#mail_page_id').val( $(this).attr('id-mail') );
        $('#mail_page').submit();
      });
    },


  /**
   * Удаление
   */
  deleteEmail: function (data) {
    const apiUrl = '/del_mail';
    const dataObj = {
      id: data.id
    };
    axios.post(apiUrl, dataObj).catch(function (error) {
      console.log(error);
    });
    $('#row_' + dataObj.id).remove();
  },


};

$(document).ready(function () {
  ListMail.init();
});

