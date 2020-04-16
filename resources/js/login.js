require('./bootstrap');

window.Vue = require('vue');

Vue.component('login', require('./components/loginComponent.vue').default);


const app = new Vue({
    el: '#app',
});
