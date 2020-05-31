import axios from 'axios';
import router from './routes';
import store from './store';

const token = localStorage.getItem('user-token');
if (token) {
    axios.defaults.headers.common['Authorization'] = 'bearer ' + token
}

axios.interceptors.response.use((response) => {
    return response;
}, function (error) {
    // Do something with response error
    if (error.response.status == 401) {
        console.log('unauthorized, logging out ...');
        delete axios.defaults.headers.common['Authorization'];
        localStorage.removeItem('user-token');
        store.state.auth.token = null;
        router.push('/login');
    }
    return Promise.reject(error.response);
});


export default axios;
