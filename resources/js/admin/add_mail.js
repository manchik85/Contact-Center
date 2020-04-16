
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


Vue.component('add-mail',   require('../components/admin/AddMailComponent.vue').default);

const app = new Vue({
  el: '#app'
});




const AddMail = {
    init: function () {

    },
};

$(document).ready(function () {
  AddMail.init();
});

