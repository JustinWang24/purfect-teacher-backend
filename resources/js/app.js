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
Vue.component('search-bar', require('./components/quickSearch/SearchBar.vue').default);
Vue.component('recruitment-plans-list', require('./components/recruitment/RecruitmentPlansList.vue').default);
Vue.component('recruitment-plan-form', require('./components/recruitment/RecruitmentPlanForm.vue').default);

import { Constants } from './common/constants';
import { Util } from './common/utils';
import { getTimeSlots } from './common/timetables';

// 学校时间段管理
if(document.getElementById('school-time-slots-manager')){
    new Vue({
        el: '#school-time-slots-manager'
    });
}
// 学校的课程管理
if(document.getElementById('school-courses-manager-app')){
    new Vue({
        el: '#school-courses-manager-app'
    });
}

// 快速定位用户的搜索框: 会更加当前的状况, 来搜索用户和学院 系等
if(document.getElementById('user-quick-search-app')){
    new Vue({
        el:'#user-quick-search-app',
        methods: {
            onItemSelected: function(payload){
                // 如果指定了 nextAction, 就跳转到其指定的页面, 否则按照 id 为用户的 id 的规则, 打开用户的 profile
                if(Util.isEmpty(payload.item.nextAction)){
                    // 按照 id 为用户的 id 的规则, 打开用户的 profile
                    const nextAction = '/verified_student/profile/edit?uuid=' + payload.item.uuid;
                    window.open(nextAction, '_blank');
                }else {
                    window.open(payload.item.nextAction, '_blank');
                }
            }
        }
    })
}

// 查看课程的授课 从教室角度, 产品课程表的程序
if(document.getElementById('school-timetable-room-viewer-app')){
    new Vue({
        el: '#school-timetable-room-viewer-app',
        data() {
            return {
                timetable: [],
                timeSlots: [],
                // 最后被选定的班级名称
                subTitle: '',
                reloading: false, // 只是课程表的预览数据是否整备加载中
                weekType: Constants.WEEK_NUMBER_ODD, // 默认是单周
                // 加载课程表所必须的项目
                schoolId: null,
                roomId: null,
                roomName: null,
                year: null,
                term: null,
            }
        },
        created() {
            this.schoolId = document.getElementById('timetable-current-school-id').dataset.school;
            this.roomId = document.getElementById('timetable-current-room-id').dataset.id;
            this.roomName = document.getElementById('timetable-current-room-name').dataset.name;
            this.year = document.getElementById('timetable-current-year').dataset.year;
            this.term = document.getElementById('timetable-current-term').dataset.term;
            for (let i = 0; i < 7; i++) {
                let rows = [];
                for (let j = 0; j < 8; j++) {
                    rows.push({});
                }
                this.timetable.push(rows);
            }

            // 把时间段数据取来, 然后去生成课程表左边第一栏
            getTimeSlots(this.schoolId).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.timeSlots = res.data.data.time_frame;
                }
            })
        },
        mounted() {
            this.refreshTimetableHandler({
                room:{
                    name: this.roomName
                }
            });
        },
        methods: {
            // 刷新课程表数据
            refreshTimetableHandler: function(payload){
                // 把数据保存到缓存中
                if(!Util.isEmpty(payload.room)){
                    this.subTitle = payload.room.name;
                }

                if(!Util.isEmpty(payload.weekType)){
                    this.weekType = payload.weekType;
                }

                this.reloading = true;
                axios.post(
                    Constants.API.TIMETABLE.LOAD_TIMETABLE,
                    {
                        room: this.roomId,
                        year: this.year,
                        term: this.term,
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
                    this.reloading = false;
                })
            },
        }
    });
}

// 查看课程的授课老师角度, 产品课程表的程序
if(document.getElementById('school-timetable-teacher-viewer-app')){
    new Vue({
        el: '#school-timetable-teacher-viewer-app',
        data() {
            return {
                timetable: [],
                timeSlots: [],
                // 最后被选定的班级名称
                subTitle: '',
                reloading: false, // 只是课程表的预览数据是否整备加载中
                weekType: Constants.WEEK_NUMBER_ODD, // 默认是单周
                // 加载课程表所必须的项目
                schoolId: null,
                teacherId: null,
                teacherName: null,
                year: null,
                term: null,
            }
        },
        created() {
            this.schoolId = document.getElementById('timetable-current-school-id').dataset.school;
            this.teacherId = document.getElementById('timetable-current-teacher-id').dataset.id;
            this.teacherName = document.getElementById('timetable-current-teacher-name').dataset.name;
            this.year = document.getElementById('timetable-current-year').dataset.year;
            this.term = document.getElementById('timetable-current-term').dataset.term;
            for (let i = 0; i < 7; i++) {
                let rows = [];
                for (let j = 0; j < 8; j++) {
                    rows.push({});
                }
                this.timetable.push(rows);
            }

            // 把时间段数据取来, 然后去生成课程表左边第一栏
            getTimeSlots(this.schoolId).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.timeSlots = res.data.data.time_frame;
                }
            })
        },
        mounted() {
            this.refreshTimetableHandler({
                teacher:{
                    name: this.teacherName
                }
            });
        },
        methods: {
            // 刷新课程表数据
            refreshTimetableHandler: function(payload){
                // 把数据保存到缓存中
                if(!Util.isEmpty(payload.teacher)){
                    this.subTitle = payload.teacher.name;
                }

                if(!Util.isEmpty(payload.weekType)){
                    this.weekType = payload.weekType;
                }

                this.reloading = true;
                axios.post(
                    Constants.API.TIMETABLE.LOAD_TIMETABLE,
                    {
                        teacher: this.teacherId,
                        year: this.year,
                        term: this.term,
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
                    this.reloading = false;
                })
            },
        }
    });
}

// 查看某个课程的课程表的程序
if(document.getElementById('school-timetable-course-viewer-app')){
    new Vue({
        el: '#school-timetable-course-viewer-app',
        data() {
            return {
                timetable: [],
                timeSlots: [],
                // 最后被选定的班级名称
                subTitle: '',
                reloading: false, // 只是课程表的预览数据是否整备加载中
                weekType: Constants.WEEK_NUMBER_ODD, // 默认是单周
                // 加载课程表所必须的项目
                schoolId: null,
                courseId: null,
                courseName: null,
                year: null,
                term: null,
            }
        },
        created() {
            this.schoolId = document.getElementById('timetable-current-school-id').dataset.school;
            this.courseId = document.getElementById('timetable-current-course-id').dataset.id;
            this.courseName = document.getElementById('timetable-current-course-name').dataset.name;
            this.year = document.getElementById('timetable-current-year').dataset.year;
            this.term = document.getElementById('timetable-current-term').dataset.term;
            for (let i = 0; i < 7; i++) {
                let rows = [];
                for (let j = 0; j < 8; j++) {
                    rows.push({});
                }
                this.timetable.push(rows);
            }

            // 把时间段数据取来, 然后去生成课程表左边第一栏
            getTimeSlots(this.schoolId).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.timeSlots = res.data.data.time_frame;
                }
            })
        },
        mounted() {
            this.refreshTimetableHandler({
                course:{
                    name: this.courseName
                }
            });
        },
        methods: {
            // 刷新课程表数据
            refreshTimetableHandler: function(payload){
                // 把数据保存到缓存中
                if(!Util.isEmpty(payload.teacher)){
                    this.subTitle = payload.teacher.name;
                }

                if(!Util.isEmpty(payload.weekType)){
                    this.weekType = payload.weekType;
                }

                this.reloading = true;
                axios.post(
                    Constants.API.TIMETABLE.LOAD_TIMETABLE,
                    {
                        course: this.courseId,
                        year: this.year,
                        term: this.term,
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
                    this.reloading = false;
                })
            },
        }
    });
}

// 查看某个班的课程表的程序
if(document.getElementById('school-timetable-grade-viewer-app')){
    new Vue({
        el: '#school-timetable-grade-viewer-app',
        data() {
            return {
                timetable: [],
                timeSlots: [],
                // 最后被选定的班级名称
                subTitle: '',
                reloading: false, // 只是课程表的预览数据是否整备加载中
                weekType: Constants.WEEK_NUMBER_ODD, // 默认是单周
                // 加载课程表所必须的项目
                schoolId: null,
                gradeId: null,
                gradeName: null,
                year: null,
                term: null,
            }
        },
        created() {
            this.schoolId = document.getElementById('timetable-current-school-id').dataset.school;
            this.gradeId = document.getElementById('timetable-current-grade-id').dataset.id;
            this.gradeName = document.getElementById('timetable-current-grade-name').dataset.name;
            this.year = document.getElementById('timetable-current-year').dataset.year;
            this.term = document.getElementById('timetable-current-term').dataset.term;
            for (let i = 0; i < 7; i++) {
                let rows = [];
                for (let j = 0; j < 8; j++) {
                    rows.push({});
                }
                this.timetable.push(rows);
            }

            // 把时间段数据取来, 然后去生成课程表左边第一栏
            getTimeSlots(this.schoolId).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.timeSlots = res.data.data.time_frame;
                }
            })
        },
        mounted() {
            this.refreshTimetableHandler({
                grade:{
                    name: this.gradeName
                }
            });
        },
        methods: {
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
                        grade: this.gradeId,
                        year: this.year,
                        term: this.term,
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
        }
    });
}

// 课程表管理程序
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
            // this._getAllTimeSlots(this.schoolId);
            for (let i = 0; i < 7; i++) {
                let rows = [];
                for (let j = 0; j < 8; j++) {
                    rows.push({});
                }
                this.timetable.push(rows);
            }
            // 把时间段数据取来, 然后去生成课程表左边第一栏
            getTimeSlots(this.schoolId).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.timeSlots = res.data.data.time_frame;
                }
            })
        },
        methods: {
            // 来自 Preview 格子元素的点击事件最终处理函数
            createNewByClickHandler: function(payload){
                Util.pageScrollTo();
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
                });
            }
        }
    });
}

// 后台的招生计划的管理程序
if(document.getElementById('school-recruitment-manager-app')){
    new Vue({
        el:'#school-recruitment-manager-app',
        data(){
            return {
                form:{},
                year:'',
                years:[],
                plans:[],
                reloading: false,
                pageSize:20,
                schoolId: null,
                userUuid: null,
                flag: true,
                // 控制表单是否显示
                showRecruitmentPlanFormFlag: false,
            }
        },
        created(){
            const el = document.getElementById('plan-manager-school-id');
            this.schoolId = el.dataset.id;
            this.userUuid = el.dataset.uuid;

            this.year = (new Date()).getFullYear() + 1;

            this.years.push(this.year + 1);
            this.years.push(this.year);
            this.years.push(this.year - 1);

            this._resetFormData();
        },
        watch:{
            'year': function(newVal) {
                this.loadPlans(0);
            }
        },
        methods: {
            // 创建新招生计划
            createNewPlan: function(){
                this._resetFormData();
                this.flag = !this.flag;
                this.showRecruitmentPlanFormFlag = true;
            },
            onEditPlanHandler: function(payload){
                this.showRecruitmentPlanFormFlag = true;
                this.form = Util.GetItemById(payload.plan.id, this.plans);
                this.$message('您正在编辑招生计划: ' + this.form.title);
                this.flag = !this.flag;
            },
            // 当删除按钮被点击
            onDeletePlanHandler: function(payload){
                axios.post(
                    Constants.API.RECRUITMENT.DELETE_PLAN,
                    {version: Constants.VERSION, plan: payload.plan.id, user: this.userUuid }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const idx = Util.GetItemIndexById(payload.plan.id, this.plans);
                        if(idx > -1){
                            this.plans.splice(idx, 1);
                            if(payload.plan.id === this.form.id){
                                // 正好删除的是正在被编辑的计划, 那么重置编辑表单
                                this._resetFormData();
                                this.flag = !this.flag;
                            }
                            this.$message({
                                message: '招生计划: ' + payload.plan.title + '已被删除',
                                type: 'success'
                            });
                        }
                    }
                    else{
                        this.$message.error('无法删除招生计划');
                    }
                })
            },
            // 当新计划被成功创建
            newPlanCreatedHandler: function(payload){
                this.showRecruitmentPlanFormFlag = false;
                this.loadPlans(0);
            },
            planUpdatedHandler: function(payload){
                this.showRecruitmentPlanFormFlag = false;
                this.loadPlans(0);
            },
            loadPlans: function(pageNumber){
                this.plans = [];
                axios.post(
                    Constants.API.RECRUITMENT.LOAD_PLANS,
                    {
                        school: this.schoolId,
                        pageNumber: pageNumber,
                        pageSize: this.pageSize,
                        year: this.year,
                        uuid: this.userUuid,// 用户 uuid, 用来加载特定的招聘计划
                        version: Constants.VERSION
                    }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.plans = res.data.data.plans;
                        if(this.plans.length > 0){
                            this.form = this.plans[0];
                        }
                    }
                });
            },
            _resetFormData: function(){
                this.form = {
                    id:'',
                    major_id:'',
                    major_name:'',
                    type:'',
                    title:'',
                    start_at:'',
                    end_at:'',
                    description:'',
                    tease:'',
                    tags:'',
                    fee:'',
                    hot:false,
                    seats:'',
                    grades_count:'',
                    year:this.year,
                    manager_id: '',
                    enrol_manager: '',
                    target_students: '',
                    student_requirements: '',
                    how_to_enrol: '',
                    manager_name: '',
                    enrol_manager_name: '',
                };
            }
        }
    });
}

// 后台的管理招生计划的报名表的管理程序
if(document.getElementById('registration-forms-list-app')){
    new Vue({
        el: '#registration-forms-list-app',
        data(){
            return {
                form: {
                    note: '',
                    currentId: '',
                    approved: false,
                },
                currentName: '',
                showNoteFormFlag: false,
                rejectNoteFormFlag: false,
                userUuid: null,
            }
        },
        created(){
            this.userUuid = document.getElementById('current-manager-uuid').dataset.id;
        },
        methods: {
            showNotesForm: function(id, name){
                this.form.note = '';
                this.form.currentId = id;
                this.currentName = name;
                this.form.approved = true;
                this.showNoteFormFlag = true;
                this.rejectNoteFormFlag = false;
            },
            showRejectForm: function(id, name){
                this.form.note = '';
                this.form.currentId = id;
                this.currentName = name;
                this.form.approved = false;
                this.showNoteFormFlag = false;
                this.rejectNoteFormFlag = true;
            },
            submit: function(){
                axios.post(
                    Constants.API.REGISTRATION_FORM.APPROVE_OR_REJECT,
                    {form: this.form, uuid: this.userUuid}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$notify({
                            title: '成功',
                            message: res.data.message,
                            type: 'success'
                        });
                        // 从新加载页面
                        window.location.reload();
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message: res.data.message
                        });
                    }
                })
            }
        }
    });
}

// 后台录取学生的管理程序
if(document.getElementById('enrol-registration-forms-app')){
    new Vue({
        el: '#enrol-registration-forms-app',
        data(){
            return {
                form: {
                    note: '',
                    currentId: '',
                    enrolled: false,
                },
                currentName: '',
                showNoteFormFlag: false,
                rejectNoteFormFlag: false,
                userUuid: null,
            }
        },
        created(){
            this.userUuid = document.getElementById('current-manager-uuid').dataset.id;
        },
        methods: {
            showNotesForm: function(id, name){
                this.form.note = '';
                this.form.currentId = id;
                this.currentName = name;
                this.form.enrolled = true;
                this.showNoteFormFlag = true;
                this.rejectNoteFormFlag = false;
            },
            showRejectForm: function(id, name){
                this.form.note = '';
                this.form.currentId = id;
                this.currentName = name;
                this.form.enrolled = false;
                this.showNoteFormFlag = false;
                this.rejectNoteFormFlag = true;
            },
            submit: function(){
                axios.post(
                    Constants.API.REGISTRATION_FORM.ENROL_OR_REJECT,
                    {form: this.form, uuid: this.userUuid}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$notify({
                            title: '成功',
                            message: res.data.message,
                            type: 'success'
                        });
                        // 从新加载页面
                        window.location.reload();
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message: res.data.message
                        });
                    }
                })
            }
        }
    });
}