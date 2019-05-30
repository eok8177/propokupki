require('./bootstrap');

import Vue from 'vue'
import App from '@/views/App'
import router from '@/router'
import Meta from 'vue-meta';
import VuePaginate from 'vue-paginate'
Vue.use(VuePaginate)
Vue.use(Meta);
const app = new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
