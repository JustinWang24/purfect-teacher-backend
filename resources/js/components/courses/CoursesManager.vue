<template>
    <div class="courses-manager-wrap">
        <div class="courses-list-col">
            <p class="courses-list-title">
                课程列表 &nbsp;
                <el-button icon="el-icon-circle-plus" type="primary" @click="newCourseForm">添加必修课</el-button>
                <el-button icon="el-icon-circle-plus" type="success" @click="newElectiveCourseForm">添加选修课</el-button>
            </p>
            <courses-list
                    :courses="courses"
                    :time-slots="timeSlots"
                    v-on:course-view="onCourseNeedView"
                    v-on:course-delete="onCourseNeedDelete"
                    v-on:course-edit="onCourseNeedEdit"
                    v-on:attach-textbook="onCourseAttacheTextbook"
                    :can-delete="canDelete"
            ></courses-list>
        </div>

        <el-dialog
            title="必修课程登记表"
            :visible.sync="showCourseFormFlag"
            :fullscreen="true"
            custom-class="course-form-drawer"
        >
            <el-form :model="courseModel" :rules="rules" ref="courseModelForm" label-width="100px" class="course-form">
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="课程编号" prop="code">
                            <el-input v-model="courseModel.code" placeholder="必填: 课程编号, 请注意保证课程编号的唯一性"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="课程名称" prop="name">
                            <el-input v-model="courseModel.name" placeholder="必填: 课程名称"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="学分" prop="scores">
                            <el-input v-model="courseModel.scores" placeholder="选填: 课程学分"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="适用年级" prop="year">
                            <el-select v-model="courseModel.year" placeholder="课程针对哪个年级">
                                <el-option label="1年级" :value="1"></el-option>
                                <el-option label="2年级" :value="2"></el-option>
                                <el-option label="3年级" :value="3"></el-option>
                                <el-option label="4年级" :value="4"></el-option>
                                <el-option label="5年级" :value="5"></el-option>
                                <el-option label="6年级" :value="6"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="适用学期" prop="term">
                            <el-select v-model="courseModel.term" placeholder="课程针对哪个学期">
                                <el-option label="第一学期" :value="1"></el-option>
                                <el-option label="第二学期" :value="2"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="课程类型">
                            <el-select v-model="courseModel.optional" placeholder="课程类型">
                                <el-option label="必修课" value="0"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-form-item label="所属专业" prop="majors">
                    <el-select v-model="courseModel.majors" multiple placeholder="请选择所属专业. 留空表示对所有的专业都有效" style="width: 100%;">
                        <el-option
                                v-for="(major, idx) in majors"
                                :key="idx"
                                :label="major.name"
                                :value="major.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="授课教师" prop="teachers">
                    <el-select style="width: 100%;"
                               v-model="courseModel.teachers"
                               multiple
                               filterable
                               remote
                               :reserve-keyword="false"
                               placeholder="请输入老师姓名"
                               :default-first-option="false"
                               :remote-method="searchTeachers"
                               :loading="loading"
                               loading-text="正在查找 ...">
                        <el-option
                                v-for="(teacher, idx) in teachers"
                                :key="idx"
                                :label="teacher.name"
                                :value="teacher.name + ' - ID:'+ teacher.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="课程概述">
                    <el-input type="textarea" v-model="courseModel.desc" placeholder="可选"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="saveCourse">保存</el-button>
                    <el-button @click="showCourseFormFlag = false">取消</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <el-dialog
                title="为课程选择教材"
                :visible.sync="showChooseTextbookFormFlag"
                custom-class="course-form-drawer"
        >
            <el-form :model="attachForm" ref="attachTextbooksForm">
                <el-form-item label="所属专业" prop="majors">
                    <el-select v-model="attachForm.attachedTextbooks" multiple placeholder="请选择教材" style="width: 90%;">
                        <el-option
                                v-for="(tb, idx) in textbooks"
                                :key="idx"
                                :label="tb.name"
                                :value="tb.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="attachTextbookConfirmed">保存</el-button>
                    <el-button @click="showChooseTextbookFormFlag = false">取消</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <el-dialog
                title="选修课程登记表"
                :visible.sync="showElectiveCourseFormFlag"
                :fullscreen="true"
                custom-class="course-form-drawer"
        >
            <elective-course-form
                    :course-model="currentElectiveCourse"
                    :total-weeks="totalWeeks"
                    :time-slots="timeSlots"
                    :majors="majors"
                    :school-id="schoolId"
                    v-on:cancelled="currentElectiveCourseCancelled"
            ></elective-course-form>
        </el-dialog>

        <el-drawer
                title="课程表"
                :visible.sync="showCourseScheduleFlag"
                direction="rtl"
                size="50%">
            <el-table :data="courseSchedule">
                <el-table-column property="date" label="日期" width="150"></el-table-column>
                <el-table-column property="name" label="教师" width="120"></el-table-column>
                <el-table-column property="grade" label="班级信息"></el-table-column>
            </el-table>
        </el-drawer>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';
    import { getTimeSlots, getCourses, getMajors } from '../../common/timetables';
    import { searchTeachers } from '../../common/search';
    import { getEmptyElectiveCourseApplication } from '../../common/elective_course';
    import { loadTextbooks, attachTextbooksToCourse } from '../../common/textbook';
    import CoursesList from './CoursesList.vue';
    import ElectiveCourseForm from './ElectiveCourseForm.vue';
    export default {
        name: "CoursesManager",
        components:{
            CoursesList,ElectiveCourseForm
        },
        props: {
            schoolId: {
                type: String,
                required: true
            },
            canDelete: {
                type: Boolean,
                required: false,
                default: false
            },
            userUuid: {
                type: String,
                required: true,
            }
        },
        data(){
            return {
                courses:[],     // 已经创建的课程列表
                courseId:null,  // 当前需要编辑的课程的 ID
                showCourseFormFlag: false, // 是否显示课程表单
                currentEditingCourseIdx: null,
                // Form
                courseModel: {
                    id: null, // 课程 ID
                    code: '', // 课程编号
                    name: '', // 课程名称
                    teachers: [], // 任课教师, 可以包含多个老师
                    scores: '0', // 学分
                    majors: [], // 所属专业, 一门课可以属于多个专业共享
                    optional: '0', // 必修还是选修
                    year: '',
                    term: '', // 学期
                    desc: '',  // 课程描述
                    timeSlots: [], // 课程上课的时间段
                    dayIndexes: [], // 课程上课的时间段
                    weekNumbers: [], // 课程上课的时间段
                },
                rules:{
                    code: [
                        { required: true, message: '请输入课程编号', trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: '请输入课程名称', trigger: 'blur' }
                    ],
                    year: [
                        { required: true, message: '请选择年级', trigger: 'change' }
                    ],
                    term: [
                        { required: true, message: '请选择学期', trigger: 'change' }
                    ]
                },
                terms: Constants.TERMS,
                majors: [], // 所有可能的专业
                teachers:[], // 被搜索出的老师
                loading: false,
                passedCourseId: null,
                // 关联教科书
                textbooks:[], // 教科书
                showChooseTextbookFormFlag: false,
                attachForm:{
                    attachedTextbooks:[],
                    courseId: null
                },
                // 根据课程总结的汇总表
                showCourseScheduleFlag: false,
                courseSchedule:[],
                timeSlots:[],
                totalWeeks: 20,
                pageNumber: 0,
                // 选修课程相关
                showElectiveCourseFormFlag: false,
                currentElectiveCourse:{}
            };
        },
        created(){
            this.courseModel = getEmptyElectiveCourseApplication();
            this._getAllCourses();
            this._getAllMajors();
            // 获取一个学期会有多少周

            // 获取学校的每天的教学时间段安排
            getTimeSlots(this.schoolId).then(res => {
                if(Util.isAjaxResOk(res)){
                    this.timeSlots = res.data.data.time_frame;
                    this.totalWeeks = res.data.data.total_weeks;
                }
            })
        },
        methods: {
            _getAllCourses: function(){
                getCourses(this.schoolId, this.pageNumber).then(res => {
                    if(res.data.code === Constants.AJAX_SUCCESS){
                        this.courses = res.data.data.courses;
                    }
                })
            },
            // 课程要关联教科书
            onCourseAttacheTextbook: function(payload){
                if(this.textbooks.length === 0){
                    loadTextbooks(this.schoolId).then(res => {
                        this.textbooks = res.data.data.textbooks;
                    })
                }
                this.attachForm.attachedTextbooks = [];
                this.attachForm.courseId = payload.course.id;
                this.showChooseTextbookFormFlag = true;
            },
            attachTextbookConfirmed: function(){
                attachTextbooksToCourse(this.schoolId, this.attachForm.courseId, this.attachForm.attachedTextbooks)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            // 关联成功
                            this.showChooseTextbookFormFlag = false;
                            // 刷新
                            window.location.reload();
                        }
                    })
            },
            onNewCourseCreated: function(payload){
                this.courses.unshift(payload);
            },
            onNewCourseUpdated: function(payload){
                console.log(payload);
            },
            whenCourseFormClosed: function(done){
                console.log(done);
            },
            _resetCourseModule: function(model){
                this.courseModel.id = null;
                this.courseModel.code = '';
                this.courseModel.name = '';
                this.courseModel.teachers = [];
                this.courseModel.majors = [];
                this.courseModel.scores = '0';
                this.courseModel.year = '';
                this.courseModel.term = '';
                this.courseModel.desc = '';

                if(typeof model !== "undefined"){
                    this.courseModel.id = model.id;
                    this.courseModel.code = model.code;
                    this.courseModel.name = model.name;
                    this.courseModel.scores = model.scores;
                    this.courseModel.year = model.year;
                    this.courseModel.term = model.term;
                    this.courseModel.desc = model.desc;
                    this.teachers = model.teachers;
                    const that = this;
                    model.majors.forEach(function(item){
                        that.courseModel.majors.push(item.id);
                    });
                    model.teachers.forEach(function(item){
                        that.courseModel.teachers.push(item.id);
                    });
                }
            },
            // 当'创建课程'按钮点击
            newCourseForm: function() {
                this._resetCourseModule();
                this.showCourseFormFlag = true;
            },
            onDrawerClosed: function(){
                this.showCourseFormFlag = false;
            },
            // 当课程名被点击, 去加载该课程的课程表
            onCourseNeedView: function(payload){
                this.showCourseScheduleFlag = true;
            },
            // 当需要删除的时候
            onCourseNeedDelete: function(payload){
                this.$confirm('此操作将永久删除该课程, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post(
                        Constants.API.DELETE_COURSE,{course: payload.course.uuid}
                    ).then(res=>{
                        if(Util.isAjaxResOk(res)){
                            this.courses.splice(payload.idx, 1);
                            this.$notify({
                                title: '成功',
                                message: '删除成功',
                                type: 'success',
                                position: 'bottom-right'
                            });
                        }
                    });
                }).catch(() => {
                    this.$notify.info({
                        title: '消息',
                        message: '删除操作已取消',
                        position: 'bottom-right'
                    });
                });
            },
            onCourseNeedEdit: function(payload){
                this.showCourseFormFlag = true;
                this.currentEditingCourseIdx = payload.idx;
                this._resetCourseModule(this.courses[payload.idx]);
            },
            // Form
            saveCourse: function(){
                axios.post(
                    Constants.API.SAVE_COURSE,{course: this.courseModel, school: this.schoolId}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        // 更新本地的模型对象
                        this.courseModel.id = res.data.data.id;
                        this.$notify({
                            title: '成功',
                            message: '课程已经保存成功',
                            type: 'success',
                            position: 'bottom-right'
                        });
                        this._getAllCourses();
                        this.onDrawerClosed();
                    }
                    else{
                        this.$notify.error({
                            title: '错误',
                            message: '无法保存课程数据, 请稍候再试或联系管理员',
                            position: 'bottom-right'
                        });
                    }
                });
            },
            // 远程搜索老师
            searchTeachers: function(teacherName){
                if (teacherName !== '') {
                    this.loading = true;
                    // 从服务器获取老师信息
                    searchTeachers(
                        this.schoolId,
                        teacherName,
                        this.courseModel.majors
                    ).then(res => {
                        this.loading = false;
                        if(Util.isAjaxResOk(res) && res.data.data.teachers.length > 0){
                            this.teachers = res.data.data.teachers;
                        }else{
                            this.teachers = [];
                        }
                    });
                }
            },
            // 获取所有可能的专业列表
            _getAllMajors: function () {
                getMajors(this.schoolId, this.pageNumber).then(res=>{
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

            // 选修课相关操作
            newElectiveCourseForm: function(){
                this.showElectiveCourseFormFlag = true;
            },
            currentElectiveCourseCancelled: function(payload){
                this.showElectiveCourseFormFlag = false;
            }
        }
    }
</script>

<style scoped lang="scss">
.courses-manager-wrap{
    display: flex;
    flex-direction: column;
    .courses-list-title{
        font-size: 18px;
        padding: 10px;
        text-align: right;
    }
}
</style>