import Vuex from 'vuex';
import axios from './axios'

export default {
    state: {
        auth: {
            token: localStorage.getItem('user-token') || '',
            type: "bearer",
            error: {},
            loading: false,
        },
        profile: {},
        todos: []
    },
    getters: {
        isAuth: state => {
            return !!state.auth.token;
        },
        token: state => {
            return state.auth.token;
        },
        profileName: state => {
            return state.profile ? state.profile.name : '';
        },
        profile: state => {
            return state.profile || {};
        },
        todos: (state)=> {
            return state.todos
        }
    },
    mutations: {
        setToken(state, payload) {
            state.auth.token = payload.token;
        },
        setProfile(state, payload) {
            // mutate state
            state.profile = payload.data.data;
        },
        authLoading(state) {
            state.auth = Object.assign({}, {...state.auth, loading: true, error: {}})
        },
        authError(state, payload) {
            state.auth.error = payload.data.errors;
        },
        authSuccess(state, payload) {
            state.auth = Object.assign({}, {
                ...state.auth,
                token: payload.access_token,
                type: payload.token_type,
                error: {},
            });
        },
        logout(state, payload) {
            state.auth = {};
        },
        errorProfile(state, payload) {
            console.log(payload);
        },
        setTodos(state, payload) {
            state.todos = payload.data;
        },
        makeTodoCompleted(state, payload) {
            state.todos.map(todo => {
                if (todo.id == payload.id) {
                    todo.status = true;
                }
                return todo;
            });
        },
        addTodo(state, payload) {
            state.todos = [payload.data, ...state.todos];
        }
    },
    actions: {
        token(context) {
            context.commit('setToken')
        },
        login({commit, dispatch}, user) {
            return new Promise((resolve, reject) => {
                commit('authLoading');
                axios({url: 'api/auth/login', data: user, method: 'POST'})
                    .then(resp => {
                        const token = resp.data.access_token;
                        localStorage.setItem('user-token', token);
                        // Add the following line:
                        axios.defaults.headers.common['Authorization'] = resp.data.token_type + ' ' + token;
                        commit('authSuccess', resp.data);
                        dispatch('profile');
                        resolve(resp)
                    })
                    .catch(err => {
                        console.log(err);
                        commit('authError', err);
                        localStorage.removeItem('user-token');
                        reject(err)
                    })
            })
        },
        logout({commit, dispatch}) {
            return new Promise((resolve, reject) => {
                commit('logout');
                localStorage.removeItem('user-token');
                // remove the axios default header
                delete axios.defaults.headers.common['Authorization'];
                resolve()
            })
        },
        profile({commit, dispatch}) {
            return new Promise((resolve, reject) => {
                axios({url: 'api/profile', method: 'get'})
                    .then(resp => {
                        commit('setProfile', resp);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('errorProfile', err.response);
                        localStorage.removeItem('user-token');
                        reject(err)
                    })
            })
        },
        editProfile({commit, dispatch}, data) {
            return new Promise((resolve, reject) => {
                axios({url: 'api/profile', method: 'put', data})
                    .then(resp => {
                        commit('setProfile', resp);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('errorProfile', err.response);
                    })
            })
        },
        todos({commit, dispatch}) {
            return new Promise((resolve, reject) => {
                axios({url: `api/users/${this.state.profile.id}/todos`, method: 'get',})
                    .then(resp => {
                        commit('setTodos', resp.data);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('errorTodos', err.response);
                    })
            })
        },
        completeTodo({commit, dispatch}, todo) {
            return new Promise((resolve, reject) => {
                axios({url: `api/users/${this.state.profile.id}/todos/${todo.id}/complete`, method: 'put',})
                    .then(resp => {
                        commit('makeTodoCompleted', todo);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('errorTodos', err.response);
                    })
            })
        },
        newTodo({commit, dispatch}, data) {
            return new Promise((resolve, reject) => {
                axios({url: `api/users/${this.state.profile.id}/todos`, method: 'post', data})
                    .then(resp => {
                        commit('addTodo', resp.data);
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('errorTodos', err.response);
                    })
            })
        },
    }
};
