
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
    $('#date_complete').datepicker(
      {
          todayHighlight: true,
          language: 'ru',
          format: 'dd.mm.yyyy',
          templates: controls
      });
};

$(document).ready(function () {
    runDatePicker();
});
