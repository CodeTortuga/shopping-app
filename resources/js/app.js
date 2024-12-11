import Vue from 'vue';
import App from './vue/app.vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle';

new Vue({
    render: (h) => h(App),
}).$mount('#app');

