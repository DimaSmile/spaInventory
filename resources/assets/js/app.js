import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
// import Dashboard from './components/Dashboard.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'

require('./bootstrap');

window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(Vuetify)

Vue.use(VueAxios, axios);
// axios.defaults.baseURL = 'http://localhost:8080/api';
axios.defaults.baseURL = 'http://inventory.loc:80/api';

const router = new VueRouter({
    mode: 'history',
    routes: [{
        path: '/:category_id?/:category_name?',
        name: 'home',
        component: Home,
        props: true
    },
    //if you want enable registration uncomment this block
    {
        path: '/register', redirect: '/'
    //     name: 'register',
    //     component: Register,
    //     meta: {
    //         auth: false
    //     }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    // {
    //     path: '/dashboard/:category_id?/:category_name?',
    //     name: 'dashboard',
    //     component: Dashboard,
    //     props: true,
    //     // meta: {
    //     //     auth: true
    //     // }
    // }
    ]
});

// new Vue({
//     el: '#app',
//     router: router,
//     render: app => app(App)
// });

Vue.router = router
Vue.use(require('@websanova/vue-auth'), {
   auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});

App.router = Vue.router
new Vue(App).$mount('#app');