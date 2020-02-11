// require('babel-polyfill');
// window._ = require('lodash');
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
import 'element-ui/lib/theme-chalk/index.css';

import {
    Button, Card, Row, Col, Drawer, Image, Badge, Divider, Tag, Form, FormItem, Input,
    Select, Option, Switch, Alert, Message, MessageBox, DatePicker, Timeline, TimelineItem, TimePicker
} from 'element-ui';

Vue.component(Button.name, Button);
Vue.component(Card.name, Card);
Vue.component(Row.name, Row);
Vue.component(Col.name, Col);
Vue.component(Image.name, Image);
Vue.component(Badge.name, Badge);
Vue.component(Divider.name, Divider);
Vue.component(Tag.name, Tag);
Vue.component(Form.name, Form);
Vue.component(FormItem.name, FormItem);
Vue.component(Input.name, Input);
Vue.component(Select.name, Select);
Vue.component(Option.name, Option);
Vue.component(Switch.name, Switch);
Vue.component(Drawer.name, Drawer);
Vue.component(Message.name, Message);
Vue.component(MessageBox.name, MessageBox);
Vue.component(Alert.name, Alert);
Vue.component(DatePicker.name, DatePicker);
Vue.component(Timeline.name, Timeline);
Vue.component(TimelineItem.name, TimelineItem);
Vue.component(TimePicker.name, TimePicker);

Vue.prototype.$alert = MessageBox.alert;
Vue.prototype.$confirm = MessageBox.confirm;
Vue.prototype.$message = Message;

Vue.component('major-cards', require('../components/statics/MajorCards.vue').default);
Vue.component('major-registration-form', require('../components/statics/MajorRegistrationForm.vue').default);
Vue.component('file-manager-mobile', require('../components/fileManager/FileManagerMobile.vue').default);
Vue.component('node-mobile', require('../components/pipeline/NodeMobile.vue').default);

require('../includes/frontend/student_registration_app');// 学生报名页面程序

// 学生启动申请流程
require('../includes/pipeline/flow_open_app');
require('../includes/backend/student_homepage_app');
require('../includes/pipeline/flow_view_history_app');
require('../includes/timetable/student_view');   // 课表页
require('../includes/timetable/student_detail'); // 课堂详情页