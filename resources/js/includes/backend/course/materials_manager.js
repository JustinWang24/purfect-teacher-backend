// 学校的课程管理
import {Util} from "../../../common/utils";
import {saveMaterial, loadMaterial,deleteMaterial, loadLectureByIndex} from '../../../common/course_material';

$(document).ready(function(){
    if(document.getElementById('course-materials-manager-app')){
        new Vue({
            el: '#course-materials-manager-app',
            data(){
                return {
                    showEditor: false, // 是否显示富文本编辑器
                    showMaterialForm: true, // 是否显示富文本编辑器
                    course:null,
                    teacher:null,
                    notes:{
                        teacher_notes:''
                    },
                    logs:[], // 当前课程的教学日志
                    configOptions:{},
                    types:[],
                    courseMaterialModel:{
                        id:null,
                        teacher_id:null,
                        course_id:null,
                        type: null,
                        index: null,
                        description: null,
                        url: null,
                        media_id: 0
                    },
                    logModel:{
                        id:null,
                        title: '',
                        content: '',
                    },
                    // 目前被加载的课件材料
                    lecture: {
                        title:'',
                        summary:'',
                    },
                    // 通过文件管理器来选择文件的功能所需
                    showFileManagerFlag: false,
                    // 教学日志表单控制
                    showLogEditor: false,
                    selectedFile:null,
                    // 导航菜单用
                    activeIndex: '1',
                    // 课时选择器
                    highlight: 1,
                    myCourseVisible: false, // 我的课程
                    courseIndexerVisible: false, // 选择课件
                    // 是否在从服务器加载数据中
                    loadingData: false,
                    // 所有该教师教授的当前的课程的班级集合
                    grades:[],
                    currentGradeId: null, // 当前选中的班级
                }
            },
            created: function(){
                const dom = document.getElementById('app-init-data-holder');
                this.course = JSON.parse(dom.dataset.course);
                this.teacher = JSON.parse(dom.dataset.teacher);
                this.grades = JSON.parse(dom.dataset.grades);
                this.configOptions = Util.getWysiwygGlobalOption(this.teacher.uuid);
                this.courseMaterialModel.teacher_id = this.teacher.id;
                this.courseMaterialModel.course_id = this.course.id;
                // 加载教师的notes
                axios.post(
                    '/teacher/course/materials/load-teacher-note',
                    {teacher: this.teacher.id, course_id: this.course.id}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.notes = res.data.data.note;
                        this.logs = res.data.data.logs;
                    }
                });
            },
            methods:{
                // 导航菜单的处理
                handleMenuSelect: function(key, keyPath){
                    if(keyPath[0] === '2'){
                        this.courseIndexerVisible = true;
                    }else if(keyPath[0] === '3'){
                        const arr = keyPath[1].split('-');
                        this.currentGradeId = arr[1];
                        console.log('选中班级', this.currentGradeId);
                    }
                    this.activeIndex = keyPath[0];
                },
                // 导航菜单处理结束
                /**
                 *
                 */
                switchCourseIndex: function(payload){
                    this.courseIndexerVisible = false;
                    this.highlight = payload.index;
                    this.loadingData = true;
                    // 去加载当前课程的第 payload.index 节课的数据
                    loadLectureByIndex(payload.index, this.teacher.id, this.course.id)
                        .then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.lecture = res.data.data.lecture;
                            }
                            this.loadingData = false;
                        })
                },
                showNotesEditor: function(){
                    this.showEditor = !this.showEditor;
                },
                // 保存教学计划
                saveNotes: function(){
                    axios.post(
                        '/teacher/course/materials/save-teacher-note',
                        {notes: this.notes}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.showEditor = false;
                            this.$message({
                                type:'success',
                                message:'课程简介保存成功'
                            })
                        }
                    });
                },
                showLogEditorHandler: function(log){
                    if(Util.isEmpty(log)){
                        // 新增
                        this.logModel.id = null;
                        this.logModel.title = '';
                        this.logModel.content = '';
                    }else{
                        this.logModel.id = log.id;
                        this.logModel.title = log.title;
                        this.logModel.content = log.content;
                    }
                    this.showLogEditor = true;
                },
                // 保存教学日志
                saveLog: function(){
                    axios.post(
                        '/teacher/course/materials/save-log',
                        {log: this.logModel, teacher: this.teacher.id, course_id: this.course.id}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.showLogEditor = false;
                            this.$message({
                                type:'success',
                                message:'教学日志保存成功'
                            });
                            if(Util.isEmpty(this.logModel.id)){
                                // 新增
                                this.logs.unshift({
                                    id: res.data.data.id,
                                    title: this.logModel.title,
                                    content: this.logModel.content
                                })
                            }else{
                                const idx = Util.GetItemIndexById(this.logModel.id, this.logs);
                                this.logs[idx].title = this.log.title;
                                this.logs[idx].content = this.log.content;
                            }
                        }
                    });
                },
                // 删除教学日志
                deleteLog: function(log){
                    console.log(log);
                },
                editMaterial: function (id) {
                    loadMaterial(id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.courseMaterialModel = res.data.data.material;
                        }
                        else{
                            this.$message.error('无法加载课件');
                        }
                    })
                },
                deleteMaterial: function(id){
                    this.$confirm('此操作将永久删除该课件, 是否继续?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        deleteMaterial(id).then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.$message({
                                    type:'success',
                                    message:'删除成功'
                                });
                                window.location.reload();
                            }
                            else {
                                this.$message.error('删除操作失败');
                            }
                        })
                    }).catch(() => {
                        this.$message({
                            type: 'info',
                            message: '已取消删除'
                        });
                    });
                },
                // 当云盘中的文件被选择
                pickFileHandler: function(payload){
                    this.selectedFile = payload.file;
                    this.showFileManagerFlag = false;
                }
            }
        });
    }
});