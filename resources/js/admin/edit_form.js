import $ from "jquery";
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


Vue.component('add-gov-role-form', require('../components/admin/AddGovRoleComponent.vue').default);
Vue.component('add-gov',    require('../components/admin/AddGovComponent.vue').default);
Vue.component('add-form',   require('../components/admin/AddFormComponent.vue').default);
Vue.component('add-name',   require('../components/admin/AddNameComponent.vue').default);
Vue.component('prior-days', require('../components/admin/PriorDaysComponent.vue').default);

const app = new Vue({
  el: '#app'
});

const EditForm = {
  init: function () {

    $('.delete_gov').on('click', function () {
      $('#gov_delete_id').val($(this).attr('id-gov'));
    });

    $('.del_gov_confirm').on('click', function () {
      const data = {
        id: $('#gov_delete_id').val(),
      };
      EditForm.deleteGov(data.id);
      $('.btn-secondary').click();
      $('#gov_' + data.id).remove();
    });



    $('.delete_form').on('click', function () {
      $('#form_delete_id').val($(this).attr('id-form'));
    });

    $('.del_form_confirm').on('click', function () {
      const data = {
        id: $('#form_delete_id').val(),
      };
      EditForm.deleteForm(data.id);
      $('.btn-secondary').click();
      $('#form_' + data.id).remove();
    });



    $('.delete_gov_role_form').on('click', function () {
      $('#gov_group_delete_id').val($(this).attr('id-form'));
    });

    $('.del_gov_group_confirm').on('click', function () {
      const data = {
        id: $('#gov_group_delete_id').val(),
      };

      EditForm.deleteFormRole(data.id);
      $('.btn-secondary').click();
      $('#role_' + data.id).remove();
    });



    $('.name_group_del').on('click', function () {
      $('#name_group_del_id').val($(this).attr('id-name'));
    });

    $('.name_group_del_confirm').on('click', function () {
      const data = {
        id: $('#name_group_del_id').val(),
      };
      EditForm.deleteFormName(data.id);
      $('.btn-secondary').click();
      $('#name_' + data.id).remove();
    });



  },

  deleteGov: function (idUser) {
    const apiUrl = '/gov_del';
    const dataObj = {
      id: idUser
    };
    axios.post(apiUrl, dataObj).catch(function (error) {
      console.log(error);
    });
  },

  deleteForm: function (idUser) {
    const apiUrl = '/form_del';
    const dataObj = {
      id: idUser
    };
    axios.post(apiUrl, dataObj).catch(function (error) {
      console.log(error);
    });

  },

  deleteFormRole: function (idUser) {
    const apiUrl = '/gov_group_del';
    const dataObj = {
      gov_group_id: idUser
    };
    axios.post(apiUrl, dataObj).catch(function (error) {
      console.log(error);
    });

  },


  deleteFormName: function (idUser) {
    const apiUrl = '/name_group_del';
    const dataObj = {
      id: idUser
    };
    axios.post(apiUrl, dataObj).catch(function (error) {
      console.log(error);
    });

  },


};

$(document).ready(function () {
  EditForm.init();
});


