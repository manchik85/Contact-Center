

window.Vue = require('vue');

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

// import Inputmask from 'inputmask';
// Vue.use(Inputmask);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('edit-common-info', require('../components/admin/EditCommonInfo.vue').default);
Vue.component('edit-password',     require('../components/admin/EditPassword.vue').default);
Vue.component('edit-password-adm', require('../components/admin/EditPasswordAdm.vue').default);
Vue.component('edit-description',  require('../components/admin/EditDescription.vue').default);

const app = new Vue({
    el: '#app'
});


