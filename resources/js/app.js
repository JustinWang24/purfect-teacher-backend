/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// 引入 Element UI 库
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);

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

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this applications
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });

import { Constants } from './common/constants';
import { Util } from './common/utils';

// 学校时间段管理
if(document.getElementById('school-time-slots-manager')){
    new Vue({
        el: '#school-time-slots-manager'
    });
}

if(document.getElementById('school-courses-manager-app')){
    new Vue({
        el: '#school-courses-manager-app'
    });
}

if(document.getElementById('school-timetable-previewer-app')){
    new Vue({
        el: '#school-timetable-previewer-app',
        data() {
            return {
                timetable: [],
                timeSlots: [],
                // 最后被选定的班级名称
                subTitle: '',
                // 控制表单的值
                shared: {
                    initWeekdayIndex: '',
                    initTimeSlotId: '',
                },
                //
                timeTableItem: {
                    id: null,
                    year:'',
                    term:1,
                    repeat_unit:1,
                    weekday_index: '',
                    time_slot_id:'',
                    // 地点
                    building_id:'',
                    room_id:'',
                    grade_id:'', // 最后被选定的班级的 id
                    course_id:'',
                    teacher_id:'',
                    published: false,
                },
                //
                schoolId: null,
                reloading: false, // 只是课程表的预览数据是否整备加载中
                weekType: Constants.WEEK_NUMBER_ODD, // 默认是单周
            }
        },
        created() {
            this.schoolId = document.getElementById('current-school-id').dataset.school;
            this._getAllTimeSlots(this.schoolId);
            for (let i = 0; i < 7; i++) {
                let rows = [];
                for (let j = 0; j < 8; j++) {
                    rows.push({});
                }
                this.timetable.push(rows);
            }
        },
        methods: {
            // 来自 Preview 格子元素的点击事件最终处理函数
            createNewByClickHandler: function(payload){
                // 检查现在是否已经选择了班级, 如果没有选择, 提示无法创建
                if(Util.isEmpty(this.timeTableItem.grade_id)){
                    this.$message.error('请您先选择课程表所要对应的班级, 才可以进行创建或修改操作!');
                }
                else{
                    this.timeTableItem.weekday_index = parseInt(payload.weekday);
                    this.timeTableItem.time_slot_id = parseInt(payload.timeSlotId);
                    this.timeTableItem.id = null;
                }
            },
            // 条目新增的事件处理
            newItemCreatedHandler: function(payload){
                this.subTitle = payload.grade.name;
                this.$notify({
                    title: '成功',
                    message: this.subTitle + '的课程表已经已经添加了新的内容, 正在刷新预览...',
                    type: 'success',
                    position: 'bottom-right'
                });
                this.refreshTimetableHandler(payload);
            },
            // 条目更新的事件处理
            itemUpdatedHandler: function(payload) {
                this.subTitle = payload.grade.name;
                this.$notify({
                    title: '成功',
                    message: this.subTitle + '的课程表已经已经修改成功, 正在刷新预览...',
                    type: 'success',
                    position: 'bottom-right'
                });
                this.refreshTimetableHandler(payload);
            },
            // 刷新课程表数据
            refreshTimetableHandler: function(payload){
                // 把数据保存到缓存中
                if(!Util.isEmpty(payload.grade)){
                    this.subTitle = payload.grade.name;
                }

                if(!Util.isEmpty(payload.weekType)){
                    this.weekType = payload.weekType;
                }

                this.reloading = true;
                axios.post(
                    Constants.API.TIMETABLE.LOAD_TIMETABLE,
                    {
                        grade: this.timeTableItem.grade_id,
                        year: this.timeTableItem.year,
                        term: this.timeTableItem.term,
                        school: this.schoolId,
                        weekType: this.weekType,
                    }
                ).then(res => {
                    if(Util.isAjaxResOk(res) && res.data.data.timetable !== ''){
                        // 表示加载到了有效的课程表
                        this.timetable = res.data.data.timetable;
                        this.$notify({
                            title: '成功',
                            message: this.subTitle + '的课程表加载完毕',
                            type: 'success',
                            position: 'bottom-right'
                        });
                    }else{
                        this.timetable = [];
                    }
                    this.reloading = false;
                }).catch(e=>{
                    console.log(e);
                    this.reloading = false;
                })
            },
            // 把时间段数据取来, 然后去生成课程表左边第一栏
            _getAllTimeSlots: function(schoolId){
                axios.post(
                    Constants.API.LOAD_STUDY_TIME_SLOTS_BY_SCHOOL,{school: schoolId}
                ).then( res => {
                    if(Util.isAjaxResOk(res)){
                        this.timeSlots = res.data.data.time_frame;
                    }
                })
            },
            // 编辑已经存在的课程表项
            editUnitByClickHandler: function(payload){
                // 从远端获取课程表项
                axios.post(
                    Constants.API.TIMETABLE.LOAD_TIMETABLE_ITEM,{id: payload.unit.id}
                ).then( res => {
                    if(Util.isAjaxResOk(res) && res.data.data.timetableItem !== ''){
                        this.timeTableItem = res.data.data.timetableItem;
                        this.$notify({
                            title: '成功',
                            message: '加载课程表项成功, 可以开始编辑了',
                            type: 'success',
                            position: 'bottom-right'
                        });
                    }
                    else{
                        // 加载失败
                        this.$message.error('您尝试加载的课程表不存在');
                    }
                })
            }
        }
    });
}