require('./bootstrap');

import Vue from 'vue'
import App from '@/views/App'
import router from '@/router'
import Meta from 'vue-meta';
import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)
Vue.use(Meta);
const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
