/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import 'es6-promise/auto'
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';


import router from './routes';
import vuexStore from './store';

Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store(vuexStore);


Vue.component('App', require('./components/App.vue').default);
Vue.component('Header', require('./components/Header.vue').default);



router.beforeEach((to, from, next) => {
    if (to.meta && to.meta.auth) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!store.getters.isAuth) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});

const app = new Vue({
    router,
    store
}).$mount('#app');
