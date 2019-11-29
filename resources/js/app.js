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
Vue.component('drag-to-sort', require('./components/dnd/DragToSort.vue').default);      // 教材表单

import { Constants } from './common/constants';
import { Util } from './common/utils';
import { getTimeSlots, getMajors, saveTimeSlot } from './common/timetables';
import { loadBuildings } from './common/facility';
import { getEmptyElectiveCourseApplication } from './common/elective_course';
import { loadTextbooksPaginate, deleteTextbook } from './common/textbook';
import { loadOrgContacts, loadGradeContacts, loadGrades } from './common/contacts';
import { saveNews, loadNews, saveSections, deleteNews, publishNews } from './common/news';

/**
 * 校历应用
 */
if(document.getElementById('school-calendar-app')){
    new Vue({
        el:'#school-calendar-app',
        data(){
            return {
                currentDate: null,
                tags: [],
                form:{
                    event_time:'',
                    tag:'',
                    content:'',
                    id:'',
                },
                events: [],
                schoolId: null,
            }
        },
        watch: {
            'currentDate': function(newDate, oldDate){
                if(!Util.isEmpty(newDate)){
                    this.form.event_time = newDate.format('YYYY-MM-DD');
                }else{
                    this.form.event_time = '';
                }
            }
        },
        created(){
            this.currentDate = moment();
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.events = JSON.parse(dom.dataset.events);// 校历内容
            this.tags = JSON.parse(dom.dataset.tags);// 校历内容
        },
        methods:{
            // 点击的时候, 会把点击的日期发过来, 如果是月份, 会发来第一天
            dateClicked: function(payload){
                this.currentDate = moment(payload);
                const ev = this._locateEvent(this.currentDate.format('YYYY-MM-DD'));
                if(!Util.isEmpty(ev)){
                    this.form = ev;
                }
                this.$message('正在编辑 ' + this.currentDate.format('YYYY-MM-DD') + ' 的校历');
            },
            onSubmit: function(){
                axios.post(
                    Constants.API.CALENDAR.SAVE,
                    {event: this.form}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            message: '校历保存成功, 正在从新加载 ...',
                            type: 'success'
                        });
                        window.location.reload();
                    }
                })
            },
            loadEvents: function(){

            },
            /**
             *
             * @param day: YYYY-MM-DD
             * @returns {*}
             */
            getEvent: function(day){
                return this._locateEvent(day);
            },
            _locateEvent: function(day){
                let result = null;
                _.each(this.events, item => {
                    if(day === item.event_time){
                        result = item;
                    }
                });
                return result;
            }
        }
    });
}

/**
 * 动态新闻的管理
 */
if(document.getElementById('school-news-list-app')){
    new Vue({
        el:'#school-news-list-app',
        data(){
            return {
                schoolId: null,
                newsForm:{
                    title: '',
                    id:'',
                    type: 1
                },
                selectedImgUrl: null,
                mediaForm:{
                    content: '',
                    media_id: null,
                    id:'',
                    position:1,
                },
                sections:[],
                newsFormFlag:false,
                sectionsFormFlag:false,
                textContentWrapFlag: false,
                mediaContentWrapFlag: false,
                showFileManagerFlag: false,
                formLabelWidth: '100px',
                loading: false,
                // 当前的新闻列表
                news:[],
                totalNews: 0,
                dndOptions:{},
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.newsForm.type = parseInt(dom.dataset.type);// 文章类型
            const injectedData = JSON.parse(dom.dataset.news);// 文章类型

            // 加载文章列表
            this.news = injectedData.data;
            this.totalNews = injectedData.total;
        },
        methods: {
            addNew: function(){
                this.newsFormFlag = true;
                this.newsForm.title = '';
            },
            publish: function(){
                publishNews(this.schoolId, this.newsForm.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            message: '发布成功',
                            type: 'success'
                        });
                        const idx = Util.GetItemIndexById(this.newsForm.id, this.news);
                        if(idx > -1){
                            this.news[idx].publish = true;
                        }
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })
            },
            saveNews: function(){
                // Todo 保存新闻
                saveNews(this.schoolId, this.newsForm).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.newsFormFlag = false;
                        this.sectionsFormFlag = true;
                        if(!Util.isEmpty(res.data.data.news)){
                            this.newsForm.id = res.data.data.news.id;
                        }
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })

            },
            cancelSaveNews: function(){
                this.newsFormFlag = false;
            },
            addNewTextSection: function(){
                this.textContentWrapFlag = true;
            },
            addNewMediaSection: function(){
                this.mediaContentWrapFlag = true;
            },
            loadNews: function(id){
                this.loading = true;
                loadNews(id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.newsForm = res.data.data.news;
                        this.sections = res.data.data.news.sections;
                        this.newsFormFlag = true;
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                    this.loading = false;
                })
            },
            deleteNews: function(id){
                this.$confirm('此操作将永久删除该动态, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loading = true;
                    deleteNews(this.schoolId, id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            window.location.reload();
                        }
                        else{
                            this.$message.error('删除失败, 请稍候再试');
                            this.loading = false;
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            pushNewSection: function(){
                if(Util.isEmpty(this.mediaForm.content) && Util.isEmpty(this.mediaForm.media_id)){
                    this.$message.error('您没有输入内容, 无法保存');
                    return;
                }

                if(!Util.isEmpty(this.mediaForm.media_id)){
                    this.mediaForm.content = this.selectedImgUrl;
                }

                saveSections(this.newsForm.id, [this.mediaForm]).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const media = {};
                        const keys = Object.keys(this.mediaForm);
                        let that = this;
                        keys.forEach(function(key){
                            media[key] = that.mediaForm[key];
                        });
                        this.sections.push(media);
                        this.resetMediaForm();
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })
            },
            // 关闭添加 section 的表单
            cancelNewSection: function(type){
                if(type ===1){
                    this.textContentWrapFlag = false;
                    this.mediaForm.content = '';
                }
                else if(type ===2){
                    this.mediaContentWrapFlag = false;
                    this.mediaForm.media_id = null;
                }
            },
            resetMediaForm: function(){
                this.mediaForm.id = '';
                this.mediaForm.content = '';
                this.mediaForm.media_id = null;
                this.mediaForm.position = 1;
                this.selectedImgUrl = null;
            },
            pickFileHandler: function(payload){
                this.mediaForm.media_id = payload.file.id;
                this.selectedImgUrl = payload.file.url;
                this.showFileManagerFlag = false;
            },
        }
    })
}

/**
 * 通讯录
 */
if(document.getElementById('school-contacts-list-app')){
    new Vue({
        el:'#school-contacts-list-app',
        data(){
            return {
                contacts: [],
                mates: [],
                teachers: [],
                schoolUuid: null,
                schoolId: null,
                grades:[],
                gradeId: null,
                loading: false,
            }
        },
        watch: {
            'gradeId': function(val){
                this.loading = true;
                this.mates = [];
                this.teachers = [];
                loadGradeContacts(this.schoolUuid, this.gradeId).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.mates = res.data.data.schoolmate_list;
                        this.teachers = res.data.data.teacher_list;
                    }
                    else{
                        this.$message.error('没有找到班级的数据');
                    }
                    this.loading = false;
                })
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolUuid = dom.dataset.school;
            this.schoolId = dom.dataset.id;
            this.loadContacts();
            this.loadAllGrades();
        },
        methods: {
            loadContacts: function(){
                loadOrgContacts(this.schoolUuid).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.contacts = res.data.data.department_list;
                    }
                });
            },
            loadAllGrades: function(){
                loadGrades(this.schoolId).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.grades = res.data.data.grades;
                    }
                })
            }
        }
    });
}

/**
 * 申请类型
 */
if(document.getElementById('application-type-app')){
    new Vue({
        el:'#application-type-app',
        data(){
            return {
                type:{
                    name:'',
                    media_id:''
                },
                showFileManagerFlag: false,
                selectedImgUrl: null,
            }
        },
        methods:{
            onSubmit: function(){
                axios.post(
                    '/school_manager/students/applications-set-save',{
                        type: this.type
                    }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type: 'success',
                            message: '保存成功!'
                        });
                        window.location.href = '/school_manager/students/applications-set';
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })
            },
            pickFileHandler: function(payload){
                this.type.media_id = payload.file.id;
                this.selectedImgUrl = payload.file.url;
                this.showFileManagerFlag = false;
            },
        }
    })
}

/**
 * 组织结构
 */
if(document.getElementById('organization-app')){
    new Vue({
        el: '#organization-app',
        data(){
            return {
                maxLevel: 4,
                schoolId: 0,
                dialogFormVisible: false,
                dialogEditFormVisible: false,
                form: {
                    name: '',
                    parent_id: '',
                    level: '',
                    phone: '',
                    address: '',
                },
                parents:[],
                loading: false
            }
        },
        watch: {
            'form.level': function(newVal, oldVal){
                if(newVal && newVal !== oldVal){
                    this.loadParents(newVal);
                }
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.maxLevel = parseInt(dom.dataset.level);
        },
        methods: {
            showForm: function(){
                this.dialogFormVisible = true;
            },
            loadParents: function(level){
                axios.post(
                    '/school_manager/organizations/load-parent',
                    {level: level}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.parents = res.data.data.parents;
                    }
                })
            },
            saveOrg: function(){
                axios.post(
                    '/school_manager/organizations/save',
                    {form: this.form}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            message: this.form.name + '已经保存成功, 页面将重新加载',
                            type: 'success'
                        });
                        window.location.reload();
                    }
                })
            },
            // 显示组织机构编辑表格
            showEdit: function(id){
                this.loading = true;
                axios.post(
                    '/school_manager/organizations/load',
                    {organization_id: id}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.form = res.data.data.organization;
                    }
                    this.loading = false;
                });
                this.dialogEditFormVisible = true;
            },
            remove: function(){
                this.$confirm('此操作将永久删除该机构以及所属的下级机构, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loading = true;
                    axios.post(
                        '/school_manager/organizations/delete',
                        {organization_id: this.form.id}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                type: 'success',
                                message: '删除成功!'
                            });
                            window.location.reload();
                        }
                        this.loading = false;
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            close: function(){
                this._resetForm();
                this.dialogEditFormVisible = false;
            },
            handleClose: function(done){
                this._resetForm();
                done();
            },
            _resetForm: function(){
                const keys = Object.keys(this.form);
                let that = this;
                keys.forEach(function (key) {
                    that.form[key] = '';
                });
            }
        }
    });
}

/**
 * 迎新助手
 */
if(document.getElementById('welcome-students-manager-app')){
    new Vue({
        el:'#welcome-students-manager-app',
        data(){
            return {
                schoolId: '',
                basics:[],
                showStepSelectorFlag: false,
                welcome:{
                    stepToBeAdd:null,
                },
                progress:[], // 当前的步骤
                enrolment:{},
                task:{
                    name:'',
                    describe:'',
                    type:1,
                },
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.basics = JSON.parse(dom.dataset.basics);
        },
        methods: {
            showStepSelector: function(){
                this.showStepSelectorFlag = true;
            },
            addNewStepAction: function(){
                const item = Util.GetItemById(this.welcome.stepToBeAdd, this.basics);
                if(!Util.isEmpty(item)){
                    this.progress.push(item)
                }
            },
            resetForm: function(id){
                this.enrolment.id = null;
                this.enrolment.name = '';
                this.enrolment.enrolment_step_id = id;
                this.enrolment.school_id = this.schoolId;
                this.enrolment.campus_id = null;
                this.enrolment.describe = '';
                this.enrolment.sort = null;
                this.enrolment.user_id = null;
                this.enrolment.assists = [];
                this.enrolment.medias = [];
                this.enrolment.tasks = [];
            },
        }
    });
}

/**
 * 教材管理
 */
if(document.getElementById('textbook-manager-app')){
    new Vue({
        el:'#textbook-manager-app',
        data(){
            return {
                userUuid: null,
                schoolId: null,
                books:[],
                total:0,
                pageNumber:0,
                pageSize:2,
                showTextbookFormFlag: false,
                textbookModel:{
                    type:1,
                    status:1,
                    medias:[],
                    courses:[],
                },
                // 自动补全搜索
                queryTextbook: '',
                queryType:'0',
                // 自动补全搜索完成
                showFileManagerFlag: false,
                showConnectedCoursesFlag: false,
                bookName: '',
                courses:[],
                isLoading: false,
                // 导出
                exportModel: {
                    type: '',
                    value: ''
                },
                majors:[],
                grades:[],
                campuses:[],
                showExportGradeFlag: false,
                showExportMajorFlag: false,
                showExportCampusFlag: false,
                // 导出功能完毕
            };
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.userUuid = dom.dataset.user;
            this.schoolId = dom.dataset.school;
            this.pageSize = parseInt(dom.dataset.size);
            axios.post(
                '/api/school/load-courses',
                {school: this.schoolId}
            ).then(res=>{
                if(Util.isAjaxResOk(res)){
                    this.courses = res.data.data.courses;
                }
            });

            this.loadTextbooks();
            this.resetForm();
        },
        methods: {
            resetForm: function(){
                this.textbookModel.id = null;
                this.textbookModel.name = '';
                this.textbookModel.edition = '';
                this.textbookModel.author = '';
                this.textbookModel.press = '';
                this.textbookModel.purchase_price = 0;
                this.textbookModel.price = 0;
                this.textbookModel.introduce = '';
                this.textbookModel.school_id = this.schoolId;
                this.textbookModel.type = 1;
                this.textbookModel.status = 1;
                this.textbookModel.medias = [];// 教材关联的图片
                this.textbookModel.courses = [];// 教材关联的课程
            },
            // 当文件从云盘管理器被选择会后的处理
            pickFileHandler: function(payload){
                this.textbookModel.medias.push(payload.file);
                this.showFileManagerFlag = false;
            },
            queryTextbooksAsync: function(queryString, cb){
                const _queryString = queryString.trim();
                if(Util.isEmpty(_queryString)){
                    // 如果视图搜索空字符串, 那么不执行远程调用, 而是直接回调一个空数组
                    cb([]);
                }
                else{
                    axios.post(
                        Constants.API.TEXTBOOK.SEARCH_TEXTBOOKS,
                        {query: _queryString, school: this.schoolId, scope: this.queryType}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            cb(res.data.data.books);
                        }
                    })
                }
            },
            handleReturnedTextbookSelect: function(item){
                this.textbookModel = item;
                this.showTextbookFormFlag = true;
            },
            // 导出功能
            exportBooksSheet: function(){
                this.showExportMajorFlag = false;
                if(this.exportModel.type === 'major'){
                    const u1 = Constants.API.TEXTBOOK.EXPORT_TEXTBOOKS_BY_MAJOR + '?major_id=' + this.exportModel.value;
                    window.open(u1, '_blank');
                }
                if(this.exportModel.type === 'campus'){
                    const u1 = Constants.API.TEXTBOOK.EXPORT_TEXTBOOKS_BY_CAMPUS + '?campus_id=' + this.exportModel.value;
                    window.open(u1, '_blank');
                }
            },
            exportByGrade: function(){
                if(this.grades.length === 0){
                    // 加载班级
                    this.isLoading = true;

                }
                this.showExportGradeFlag = true;
            },
            exportByMajor: function(){
                if(this.majors.length === 0){
                    // 加载班级
                    this.isLoading = true;
                    getMajors(this.schoolId, 0).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.majors = res.data.data.majors;
                        }
                        this.isLoading = false;
                    })
                }
                this.exportModel.type = 'major';
                this.showExportMajorFlag = true;
            },
            exportByCampus: function(){
                if(this.campuses.length === 0){
                    this.isLoading = true;
                    loadBuildings(this.schoolId).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.campuses = res.data.data.campuses;
                        }
                        this.isLoading = false;
                    })
                }
                this.exportModel.type = 'campus';
                this.showExportCampusFlag = true;
            },
            // 导出功能结束
            getCourseNameText: function(courseId){
                const c = Util.GetItemById(courseId, this.courses);
                return c.name;
            },
            updateTextbookRelatedCourses: function(){
                axios.post(
                    '/teacher/textbook/update-related-courses',
                    {textbook: this.textbookModel.id, courses: this.textbookModel.courses}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.showConnectedCoursesFlag = false;
                        this.$message({
                            message: '保存成功: ' + this.textbookModel.name + '的关联课程已经更新了',
                            type: 'success'
                        });
                        // 一旦更新成功, 则刷新当前的列表
                        this.isLoading = true;
                    }
                })
            },
            // 关联课程
            connectCoursesAction: function(payload){
                // 加载该教材关联的课程
                this.textbookModel = payload.book;
                // 显示对话框
                this.showConnectedCoursesFlag = true;
            },
            addNewTextbook: function(){
                this.showTextbookFormFlag = true;
                this.resetForm();
            },
            // 编辑课本
            editBookAction: function(payload){
                this.textbookModel = payload.book;
                this.showTextbookFormFlag = true;
            },
            // 保存教材数据
            saveTextbook: function(){
                axios.post(
                    '/teacher/textbook/save',
                    {textbook: this.textbookModel}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.showTextbookFormFlag = false;
                        if(Util.isEmpty(this.textbookModel.id)){
                            // 新增教材的操作
                            this.books.unshift(res.data.data.textbook);
                        }
                        else{
                            // 更新操作
                            const idx = Util.GetItemIndexById(res.data.data.textbook.id, this.books);
                            if(idx > -1){
                                this.books[idx] = res.data.data.textbook;
                            }
                        }
                        this.resetForm();
                        this.$message({
                            message: '教材数据保存成功: ' + res.data.data.textbook.name,
                            type: 'success'
                        });
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message: res.data.message,
                            position: 'bottom-right'
                        });
                    }
                })
            },
            // 删除教材数据
            deleteBookAction: function(payload){
                deleteTextbook(payload.book.id)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            const idx = Util.GetItemIndexById(payload.book.id, this.books);
                            if(idx > -1){
                                this.books.splice(idx, 1);
                                this.$message({
                                    message: '成功的删除了教材: ' + payload.book.name,
                                    type: 'success'
                                });
                            }
                        }
                        else{
                            this.$notify.error({
                                title: '系统错误',
                                message: '删除操作失败, 请稍候再试 ...',
                                position: 'bottom-right'
                            });
                        }
                    });
            },
            cancel: function(){
                this.showTextbookFormFlag = false;
            },
            selectedFileDeleted: function(payload){
                const idx = Util.GetItemIndexByUuid(payload.file.uuid, this.textbookModel.medias);
                this.textbookModel.medias.splice(idx, 1);
                this.$message({
                    message: '取消了文件: ' + payload.file.file_name,
                    type: 'success'
                });
            },
            loadTextbooks: function(){
                this.isLoading = true;
                loadTextbooksPaginate(
                    this.schoolId, this.userUuid, this.pageNumber, this.pageSize
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.books = res.data.data.books;
                        this.total = res.data.data.total;
                        this.pageNumber = res.data.data.p;
                        this.pageSize = res.data.data.s;
                    }
                    this.isLoading = false;
                })
            },
            // Pagination 的页码点击响应事件
            goToPage: function(val){
                this.pageNumber = val -1;
                this.loadTextbooks();
            }
        }
    });
}

/**
 * 老师申请开设一门新的选修课
 */
if(document.getElementById('teacher-apply-a-new-elective-course-app')){
    new Vue({
        el:'#teacher-apply-a-new-elective-course-app',
        data(){
            return {
                majors:[],
                timeSlots:[],
                schoolId:'',
                course:{},
            };
        },
        created(){
            this.course = getEmptyElectiveCourseApplication(true);
            this.schoolId = document.getElementById('app-init-data-holder').dataset.school;
            this.applicationId = document.getElementById('app-init-data-holder').dataset.application;
            this._getAllMajors();
            this._getAllTimeSlots();
        },
        methods: {
            _getAllTimeSlots: function(){
                getTimeSlots(this.schoolId).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.timeSlots = res.data.data.time_frame;
                    }
                })
            },
            // 获取所有可能的专业列表
            _getAllMajors: function () {
                getMajors(this.schoolId, 0).then(res=>{
                    if(Util.isAjaxResOk(res) && res.data.data.majors.length > 0){
                        this.majors = res.data.data.majors;
                    }
                    else{
                        this.$notify.error({
                            title: '错误',
                            message: '无法加载专业信息列表',
                            position: 'bottom-right'
                        });
                    }
                })
            },
        }
    });
}

/**
 * 文件管理器
 */
if (document.getElementById('file-manager-app')){
    new Vue({
        el: '#file-manager-app',
        data(){
            return {
                showFileManagerFlag: false,
            }
        },
        created() {

        },
        methods: {
            showFileManager: function(){
                this.showFileManagerFlag = true;
            },
            handleClose: function(){
                this.showFileManagerFlag = false;
            }
        }
    });
}

// 学校时间段管理
if(document.getElementById('school-time-slots-manager')){
    new Vue({
        el: '#school-time-slots-manager',
        data(){
            return {
                needReload: false,
                currentTimeSlot: {
                    id:'',
                    from:'',
                    to:'',
                    name:'',
                    type:''
                },
                showEditForm: false,
                schoolUuid:'',
            }
        },
        methods:{
            editTimeSlotHandler: function(payload){
                const keys = Object.keys(payload.timeSlot);
                keys.forEach(key => {
                    this.currentTimeSlot[key] = payload.timeSlot[key];
                });
                // this.currentTimeSlot = payload.timeSlot;
                this.schoolUuid = payload.schoolUuid;
                this.showEditForm = true;
            },
            onSubmit: function () {
                if(this.currentTimeSlot.name.trim() === ''){
                    this.$message.error('作息时间表的名称不可以为空');
                    return;
                }
                if(Util.isEmpty(this.currentTimeSlot.from)  || Util.isEmpty(this.currentTimeSlot.to)){
                    this.$message.error('作息时间表的时间段不可以为空');
                    return;
                }
                if(this.currentTimeSlot.to < this.currentTimeSlot.from){
                    this.$message.error('作息时间表的结束时间不可以早于开始时间');
                    return;
                }

                saveTimeSlot(this.schoolUuid, this.currentTimeSlot)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                message: '修改成功, 作息表正在重新加载 ...',
                                type: 'success'
                            });
                            window.location.reload();
                        }
                        else{
                            this.$message.error('错了哦，这是一条错误消息');
                        }
                    });
            },
            toChangedHandler: function (to) {
                if(to < this.currentTimeSlot.from){
                    this.$message.error('作息时间表的结束时间不可以早于开始时间');
                }
            }
        }
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
                    opening_date: '',
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
            deleteForm: function(id){
                this.$confirm('此操作将删除该报名表, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    window.location.href = '/teacher/registration-forms/delete?uuid=' + id;
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
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