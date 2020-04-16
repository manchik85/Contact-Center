
// import axios from "axios";

import axios from "axios";

window.Vue = require('vue');

// import VueTheMask from 'vue-the-mask';
// Vue.use(VueTheMask);

// import Inputmask from 'inputmask';
// Vue.use(Inputmask);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app'
});


const controls = {
    leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
    rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
};

const runDatePicker = function () {
    // range picker
    $('#datepicker-5').datepicker(
      {
          todayHighlight: true,
          language: 'ru',
          format: 'dd.mm.yyyy',
          templates: controls
      });
};


const CallTasks = {
  init: function () {

    $('.load-stat').on('click', () => {
      $('#load_exel').val('');
      $('#statPage').submit();
    });

    $('.exel').on('click', () => {
      $('#load_exel').val(1);
      $('#statPage').submit();
    });


    $('.filter_clear').on('click',  () => {
      $('#date_task_start').val('');
      $('#date_task_end').val('');
      $('#date_complete_start').val('');
      $('#date_complete_end').val('');
      $('#process_name').val('');
      $('#priority').val('');
      $('#complete').val('');
      $('#client_spot').val('');
      $('#gov_name').val('');
      $('#client_fio').val('');
      $('#client_login').val('');
      $('#client_phone').val('');
      $('#client_mail').val('');
      $('#operator').val('');
      $('#developer').val('');
      $('#task_id').val('');
      $('#district').val('');

      $('#load_exel').val(0);
      $('#statPage').submit();
    });


  },


};

$(document).ready(function () {
  CallTasks.init();
    runDatePicker();
});
