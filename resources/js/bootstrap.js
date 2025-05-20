import axios from 'axios';

try {
    window.Popper = require('@popperjs/core');
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (error) {
    console.log(error)
}

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
