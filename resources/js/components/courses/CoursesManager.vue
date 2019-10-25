<template>
    <div class="courses-manager-wrap">
        <div class="courses-list-col">
            <p class="courses-list-title">
                课程列表 &nbsp;
                <el-button type="primary" @click="newCourseForm">添加课程</el-button>
            </p>
            <courses-list :courses="courses" @course-delete="onCourseNeedDelete" @course-edit="onCourseNeedEdit" :can-delete="canDelete"></courses-list>
        </div>

        <el-drawer
            title="课程登记表"
            :visible.sync="showCourseFormFlag"
            direction="rtl"
            custom-class="course-form-drawer"
        >
            <el-form :model="courseModel" :rules="rules" ref="courseModelForm" label-width="100px" class="course-form">
                <el-form-item label="课程编号" prop="code">
                    <el-input v-model="courseModel.code" placeholder="必填: 课程编号, 请注意保证课程编号的唯一性"></el-input>
                </el-form-item>
                <el-form-item label="课程名称" prop="name">
                    <el-input v-model="courseModel.name" placeholder="必填: 课程名称"></el-input>
                </el-form-item>
                <el-form-item label="学分" prop="scores">
                    <el-input v-model="courseModel.scores" placeholder="选填: 课程学分"></el-input>
                </el-form-item>
                <el-form-item label="适用年级" prop="year">
                    <el-select v-model="courseModel.year" placeholder="课程针对哪个年级">
                        <el-option label="1年级" value="1"></el-option>
                        <el-option label="2年级" value="2"></el-option>
                        <el-option label="3年级" value="3"></el-option>
                        <el-option label="4年级" value="4"></el-option>
                        <el-option label="5年级" value="5"></el-option>
                        <el-option label="6年级" value="6"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="适用学期" prop="term">
                    <el-select v-model="courseModel.term" placeholder="课程针对哪个学期">
                        <el-option label="第一学期" value="1"></el-option>
                        <el-option label="第二学期" value="2"></el-option>
                        <el-option label="第三学期" value="3"></el-option>
                        <el-option label="第四学期" value="4"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="课程类型">
                    <el-select v-model="courseModel.optional" placeholder="课程类型">
                        <el-option label="必修课" value="0"></el-option>
                        <el-option label="选修课" value="1"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="所属专业" prop="majors">
                    <el-select v-model="courseModel.majors" multiple placeholder="请选择所属专业" style="width: 100%;">
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
                               reserve-keyword
                               placeholder="请输入老师姓名"
                               :remote-method="searchTeachers"
                               :loading="loading">
                        <el-option
                                v-for="(teacher, idx) in teachers"
                                :key="idx"
                                :label="teacher.name"
                                :value="teacher.id">
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
        </el-drawer>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import CoursesList from './CoursesList.vue';
    export default {
        name: "CoursesManager",
        components:{
            CoursesList
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
                    desc: '',  // 学期
                },
                rules:{
                    code: [
                        { required: true, message: '请输入课程编号', trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: '请输入课程名称', trigger: 'blur' }
                    ],
                    // teachers: [
                    //     { type: 'array', required: true, message: '请至少选择一个任课教师', trigger: 'change' }
                    // ],
                    // majors: [
                    //     { type: 'array', required: true, message: '请至少选择一个所属专业', trigger: 'change' }
                    // ],
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
            };
        },
        created(){
            this._getAllCourses();
            this._getAllMajors();
        },
        methods: {
            _getAllCourses: function(){
                axios.post(
                    Constants.API.LOAD_COURSES_BY_SCHOOL,{school: this.schoolId}
                ).then(res => {
                    if(res.data.code === Constants.AJAX_SUCCESS){
                        this.courses = res.data.data.courses;
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
                        if(res.data.code === Constants.AJAX_SUCCESS){
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
                const isUpdate = this.courseModel.id;
                axios.post(
                    Constants.API.SAVE_COURSE,{course: this.courseModel, school: this.schoolId}
                ).then(res => {
                    if(res.data.code === Constants.AJAX_SUCCESS){
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
                    axios.post(
                        Constants.API.SEARCH_TEACHERS_BY_NAME,
                        {query: teacherName, school: this.schoolId, majors: this.majors}
                    ).then(res => {
                        this.loading = false;
                        if(res.data.code === Constants.AJAX_SUCCESS && res.data.data.teachers.length > 0){
                            this.teachers = res.data.data.teachers;
                        }else{
                            this.teachers = [];
                        }
                    });
                }
            },
            // 获取所有可能的专业列表
            _getAllMajors: function () {
                axios.post(
                    Constants.API.LOAD_MAJORS_BY_SCHOOL, {id: this.schoolId}
                ).then(res=>{
                    if(res.data.code === Constants.AJAX_SUCCESS && res.data.data.majors.length > 0){
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