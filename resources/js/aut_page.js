require('./bootstrap');

window.Vue = require('vue');

Vue.component('aut-page', require('./components/autPageComponent.vue').default);


const app = new Vue({
    el: '#app',
});
