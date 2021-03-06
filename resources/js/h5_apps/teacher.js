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

// 引入Vant库
import Vant from 'vant';
import 'vant/lib/index.css';
Vue.use(Vant);

Vue.component('news-item', require('../components/content/NewsItem.vue').default);
Vue.component('notice-item', require('../components/content/NoticeItem.vue').default);
Vue.component('file-manager-mobile', require('../components/fileManager/FileManagerMobile.vue').default);
Vue.component('file-manager', require('../components/fileManager/FileManager.vue').default);

require('../includes/backend/contacts_list_manager');
require('../includes/teacher/school_calendar_app');
require('../includes/teacher/school_attendances_app');
require('../includes/teacher/time_slots');
require('../includes/teacher/news');
require('../includes/teacher/notices');
require('../includes/teacher/managerment/devices_app'); // 设备管理
require('../includes/teacher/managerment/rooms_app'); // 房间管理
require('../includes/teacher/managerment/visitors_app'); // 访客管理
require('../includes/teacher/managerment/grades_app'); // 班级管理
require('../includes/teacher/managerment/students_list'); // 班级管理
