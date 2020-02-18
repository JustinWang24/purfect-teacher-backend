/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.moment = require('moment');

// 引入 Element UI 库
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

// 拖拽

/**

* The following block of code may be used to automatically register your
* Vue components. It will recursively scan this directory for the Vue
* components and automatically register them with their "basename".
*
* Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
*/

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('time-slots-manager', require('./components/timeline/TimeSlotsManager.vue').default);
Vue.component('courses-manager', require('./components/courses/CoursesManager.vue').default);
Vue.component('timetable-previewer', require('./components/previewer/TimetablePreviewer.vue').default);
Vue.component('timetable-item-form', require('./components/previewer/TimetableItemForm.vue').default);
Vue.component('search-bar', require('./components/quickSearch/SearchBar.vue').default);
Vue.component('recruitment-plans-list', require('./components/recruitment/RecruitmentPlansList.vue').default);
Vue.component('recruitment-plan-form', require('./components/recruitment/RecruitmentPlanForm.vue').default);
Vue.component('file-manager', require('./components/fileManager/FileManager.vue').default);
Vue.component('elective-course-form', require('./components/courses/ElectiveCourseForm.vue').default);
Vue.component('textbooks-table', require('./components/textbook/TextbooksTable.vue').default); // 教材列表
Vue.component('file-preview', require('./components/fileManager/elements/FilePreview.vue').default);      // 教材表单
// Vue.component('drag-to-sort', require('./components/dnd/DragToSort.vue').default);      // 教材表单
Vue.component('icon-selector', require('./components/misc/IconSelector.vue').default);      // 教材表单
Vue.component('node', require('./components/pipeline/Node.vue').default);      // 教材表单
Vue.component('notification-item', require('./components/message/NotificationItem.vue').default);      // 教材表单
Vue.component('organizations-selector', require('./components/organization/OrganizationsSelector').default);      // 可见范围选择器

// 仿 Moodle 的教学管理功能所用的组件
Vue.component('course-indexer', require('./components/moodle/teacher/CourseIndexer').default);      // 可见范围选择器
Vue.component('lecture', require('./components/moodle/teacher/Lecture').default);      // 课件组件
Vue.component('grade-table', require('./components/moodle/teacher/GradeTable').default);      // 班级组件

require('./includes/backend/school_calendar_app'); // 后台: 校历的管理
require('./includes/backend/notice_manager_app'); // 后台: Notice 的管理
require('./includes/backend/banner_manager_app'); // 后台: Banner 的管理
require('./includes/backend/teacher_homepage_app'); // 后台: 教师的首页
require('./includes/backend/teacher-oa-index-app'); // 后台: 教师的办公
require('./includes/backend/student_homepage_app'); // 后台: 学生的首页
require('./includes/backend/organization_app'); // 组织架构管理
require('./includes/backend/news_list_manager'); // 动态新闻的管理
require('./includes/backend/welcome_manager'); // 迎新管理
require('./includes/backend/contacts_list_manager'); // 通讯录
require('./includes/backend/textbook_manager'); // 教材
require('./includes/backend/timeslots_manager'); // 作息时间段管理

require('./includes/backend/recruitment_manager'); // 招生
require('./includes/backend/recruitment_plan_manager'); // 招生计划
require('./includes/backend/recruitment_enrol_manager'); // 招生录取

require('./includes/backend/file_manager_app'); // 云盘管理
require('./includes/backend/teacher_apply_new_course'); // 云盘管理
require('./includes/backend/user_finder_app'); // 快速定位用户
require('./includes/backend/application_type_app'); // 申请类型管理
require('./includes/backend/welcome_students'); // 申请类型管理
require('./includes/backend/adviser_editor_app'); // 系主任, 班主任, 教研组长, 班长等管理程序
require('./includes/backend/new_attendance_app'); // 新建值周管理 app
require('./includes/backend/year_manager_app'); // 年级组长管理 app
require('./includes/backend/teaching_research_group_app'); // 教研组管理 app
require('./includes/backend/teacher_profile_app'); // 教师档案，评教相关管理 app

require('./includes/backend/timetable/preview_manager'); // 课程表管理程序
require('./includes/backend/timetable/course_view'); // 课程表管理程序
require('./includes/backend/timetable/grade_view'); // 课程表管理程序
require('./includes/backend/timetable/room_view'); // 课程表管理程序
require('./includes/backend/timetable/teacher_view'); // 课程表管理程序
require('./includes/backend/course/materials_manager'); // 课件管理程序

require('./includes/backend/course/course_manager'); // 学校的课程管理
require('./includes/backend/messager/notifications_at_top'); // 顶部的消息通知应用


require('./includes/pipeline/flow_open_app'); // 用户启动流程
require('./includes/pipeline/flow_view_history_app'); // 用户查看流程详情
require('./includes/pipeline/flows_manager_app'); // 流程管理后台

require('./includes/backend/teacher_check_in'); // 后台,教师签到
require('./includes/backend/teacher_blade_index'); // 后台,教师助手
require('./includes/backend/teacher_evaluation'); // 后台,教师评分
