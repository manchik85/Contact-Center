
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


Vue.component('add-tack',   require('../components/admin/AddTackComponent.vue').default);

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
  $('#datepicker-6').datepicker(
    {
      todayHighlight: true,
      language: 'ru',
      format: 'dd.mm.yyyy',
      templates: controls
    });
};


const ListTasks = {
  init: function () {
    $(document).on("click", '.delete_user', function(){
    //$('.delete_user').on('click', function () {
      $('#name_task_modal').html($(this).attr('name-task'));
      $('#task_delete_id').val($(this).attr('id-task'));
      $('#client_id').val($(this).attr('id-client'));
    });

    $('.del_user_confirm').on('click', function () {
      const data = {
        id: $('#task_delete_id').val(),
        client_id: $('#client_id').val(),
      };
      ListTasks.deleteTask(data);
      $('.btn-secondary').click();
    });

    $(document).on("click", '.look', function(){
    //$('.look').on('click', function () {
      $('#task_page_id').val( $(this).attr('id-task') );
      $('#task_page').submit();
    });

    $(document).on("click", '.look-task', function(){
    //$('.look-task').on('click', function () {
      $('#task_operator_page_id').val( $(this).attr('id-task') );
      $('#task_operator_page').submit();
    });


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

  /**
   * Удаление
   */
  deleteTask: function (data) {
    const apiUrl = '/task_del';
    const dataObj = {
      id: data.id,
      client_id: data.client_id
    };
    axios.post(apiUrl, dataObj).catch(function (error) {
      console.log(error);
    });
    $('#row_' + dataObj.id).remove();
  },

};

$(document).ready(function () {
  ListTasks.init();
  runDatePicker();
});
