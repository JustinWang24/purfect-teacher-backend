<template>
    <div class="course-form-wrap">
        <p class="course-form-title">课程登记表</p>
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
                            v-for="(teacher, index) in teachers"
                            :key="index"
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
                <el-button>取消</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    export default {
        name: "CourseForm",
        props: {
            schoolId: { // 学校的 ID
                type: String, required: true
            },
            courseId: {
                type: String, required: false, default: null
            }
        },
        data(){
            return {
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
                terms: ['第一学期','第二学期','第三学期','第四学期'],
                majors: [], // 所有可能的专业
                teachers:[], // 被搜索出的老师
            }
        },
        created(){
        },
        mounted(){
            this._getAllMajors();
        },
        methods: {
            // 保存当前的课程对象到服务器
            saveCourse: function(){
                axios.post(
                    Constants.API.SAVE_COURSE,{course: this.courseModel, school: this.schoolId}
                ).then(res => {
                    if(res.data.error_no === Constants.AJAX_SUCCESS){
                        // Todo 保存成功, 发布一个消息出去
                    }
                    else{
                        this.$notify.error({
                            title: '错误',
                            message: '无法保存课程数据, 请稍候再试或联系管理员'
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
                        if(res.data.error_no === Constants.AJAX_SUCCESS && res.data.data.teachers.length > 0){
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
                    if(res.data.error_no === Constants.AJAX_SUCCESS && res.data.data.majors.length > 0){
                        this.majors = res.data.data.majors;
                    }
                    else{
                        this.$notify.error({
                            title: '错误',
                            message: '无法加载专业信息列表'
                        });
                    }
                })
            }
        }
    }
</script>

<style scoped lang="scss">
.course-form-wrap{
    padding: 10px;
    .course-form-title{
        line-height: 30px;
        font-size: 18px;
    }
}
</style>