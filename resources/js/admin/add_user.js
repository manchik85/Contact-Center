
window.Vue = require('vue');

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

Vue.component('add-users', require('../components/admin/addUsersComponent.vue').default);


const app = new Vue({
    el: '#app',
});
