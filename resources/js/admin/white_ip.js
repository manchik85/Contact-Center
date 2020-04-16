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


Vue.component('white_ip_add',   require('../components/admin/AddIPComponent.vue').default);

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

    },

    deleteGov: function (idUser) {
        const apiUrl = '/white_ip_del';
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


