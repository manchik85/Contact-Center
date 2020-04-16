
window.Vue = require('vue');


Vue.component('add-permission', require('../components/admin/addPermissionComponent.vue').default);


const app = new Vue({
  el: '#app',
});
