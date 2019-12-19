require('babel-polyfill');
window._ = require('lodash');
window.axios = require('axios');

const token = document.head.querySelector('meta[name="csrf-token"]');
const API_TOKEN = document.head.querySelector('meta[name="api-token"]');

if (token) {
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': token.content,
        'Authorization': 'Bearer ' + API_TOKEN.content,
        'Accept': 'application/json',
    };
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue = require('vue');
// 引入 Element UI 库
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

require('../includes/backend/contacts_list_manager');
require('../includes/teacher/school_calendar_app');
require('../includes/teacher/school_attendances_app');