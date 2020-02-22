import {Util} from "../../../common/utils";
import {Constants} from "../../../common/constants";
import {loadLectureMaterials, loadStudentHomework, deleteStudentHomework} from "../../../common/course_material";

$(document).ready(function(){
    if(document.getElementById('course-student-manager-app')){
        new Vue({
            el:'#course-student-manager-app',
            data(){
                return {
                    courseIndexerVisible: false,
                    showHomeworkForm: false,
                    homeworkModel:{
                        lecture_id: null,
                        content:''
                    },
                    homeworkSubmitUrl:'',// 保存作业的api接口地址
                    fileList:[],
                    highlight:1,
                    course:{},
                    student:{},
                    lectures:[],
                    materialTypes:[],
                    currentHomeworks:[], // 当前被加载的作业
                    currentMaterials:[], // 当前被选定的lecture的课件
                    currentLecture:null, // 当前被选定的lecture
                    formHeaders:{
                        'Authorization': 'Bearer ',
                    }
                }
            },
            computed:{
                studentHomeworkFormData: function(){
                    return {
                        body: JSON.stringify(this.homeworkModel),
                    }
                }
            },
            created(){
                const dom = document.getElementById('app-init-data-holder');
                this.course = JSON.parse(dom.dataset.course);
                this.student = JSON.parse(dom.dataset.student);
                this.materialTypes = Constants.COURSE_MATERIAL_TYPES_TEXT;
                const lectures = JSON.parse(dom.dataset.lectures);
                const keys = Object.keys(lectures);
                const that = this;
                keys.forEach(key => {
                    that.lectures.push(lectures[key]);
                });
                this.currentLecture = this.lectures[0];
                this.resetHomeworkModel();
                this.homeworkSubmitUrl = Constants.API.COURSE_MATERIAL.SUBMIT_HOMEWORK;
                this.formHeaders = {
                    'Authorization': 'Bearer ' + this.student.api_token,
                };
                loadLectureMaterials(this.currentLecture.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.currentMaterials = res.data.data.materials;
                    }
                });
                loadStudentHomework(this.course.id, this.currentLecture.idx, this.student.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.currentHomeworks = res.data.data.homeworks;
                    }
                })
            },
            methods:{
                handleMenuSelect: function(key, keyPath){
                    if(key === '1'){
                        this.courseIndexerVisible = true;
                    }
                },
                switchLecture: function(payload){
                    this.currentLecture = payload.lecture;
                    this.courseIndexerVisible = false;
                    if(Util.isEmpty(payload.lecture)){
                        this.$message.error('课件还没有准备好')
                    }
                    else{
                        this.highlight = payload.lecture.idx;
                        loadLectureMaterials(payload.lecture.id).then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.currentMaterials = res.data.data.materials;
                            }
                        });
                        loadStudentHomework(this.course.id, payload.lecture.idx, this.student.id).then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.currentHomeworks = res.data.data.homeworks;
                            }
                        })
                    }
                },
                isTypeOf: function(typeId, typeIdx){
                    return typeId === typeIdx+1;
                },
                showSubmitHomeworkForm: function () {
                    if(Util.isEmpty(this.currentLecture)){
                        this.$message.error('请先选择第几节课');
                        return;
                    }
                    this.showHomeworkForm = true;
                    this.resetHomeworkModel();
                },
                deleteHomework: function(idx, homework){
                    this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        deleteStudentHomework(homework.id).then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.currentHomeworks.splice(idx, 1);
                                this.$message({
                                    type: 'success',
                                    message: '删除成功!'
                                });
                            }
                        })
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
                    });


                },
                resetHomeworkModel: function(){
                    this.homeworkModel.student_id = this.student.id;
                    this.homeworkModel.content = '';
                    this.homeworkModel.media_id = 0;
                    this.homeworkModel.student_name = this.student.name;
                    this.homeworkModel.course_id = this.course.id;
                    this.homeworkModel.idx = this.currentLecture.idx;
                    this.homeworkModel.lecture_id = this.currentLecture.id;
                },
                submitUpload(form) {
                    this.$refs[form].validate(async valid => {
                        if (valid) {
                            // 表单验证通过后使用组件自带的方法触发上传事件
                            this.$refs.studentHomeworkRef.submit()
                        } else {
                            return false;
                        }
                    });
                },
                onHomeworkRemovedHandler(file, fileList) {
                    console.log(file, fileList);
                },
                onHomeworkSubmittedHandler(res, file) {
                    if(res.code === Constants.AJAX_SUCCESS){
                        this.$message.success('上传成功');
                        loadStudentHomework(this.course.id, this.currentLecture.idx, this.student.id).then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.currentHomeworks = res.data.data.homeworks;
                            }
                        })
                    }
                    else{
                        this.$message.error(res.message);
                    }
                    let _this = this;
                    setTimeout(function() {
                        _this.$refs.studentHomeworkRef.clearFiles();
                    }, 1000);
                    this.showHomeworkForm = false;
                },
                onHomeworkExceededHandler(files, fileList){

                }
            }
        });
    }
});