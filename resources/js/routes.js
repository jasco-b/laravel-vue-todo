import VueRouter from 'vue-router';
import Login from './components/Login.vue';
import Home from './components/Home.vue';
import Todo from "./components/Todo";
import Profile from "./components/Profile";
import NotFound from './components/NotFound';


const routes = {
    'mode': 'history',
    linkActiveClass: 'active',
    routes: [{
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            auth: true
        }
    }, {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
        {
            path: '/todos',
            name: 'todos',
            component: Todo,
            meta: {
                auth: true
            }
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile,
            meta: {
                auth: true
            }
        },
        {
            path: '/404',
            name: '404',
            component: NotFound
        },
        {
            path: '*',
            redirect: '/404'
        },
    ]
};

export default new VueRouter(
    routes // short for `routes: routes`,

);
