// 学校的课程管理
import {Constants} from "../../../common/constants";
import {Util} from "../../../common/utils";
import {saveMaterial} from '../../../common/course_material';

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
                    notes:null,
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
                    // 通过文件管理器来选择文件的功能所需
                    showFileManagerFlag: false,
                    selectedFile:null,
                }
            },
            created: function(){
                const dom = document.getElementById('app-init-data-holder');
                this.course = JSON.parse(dom.dataset.course);
                this.teacher = JSON.parse(dom.dataset.teacher);
                this.notes = JSON.parse(dom.dataset.notes);
                this.configOptions = Util.getWysiwygGlobalOption(this.teacher.uuid);
                this.courseMaterialModel.teacher_id = this.teacher.id;
                this.courseMaterialModel.course_id = this.course.id;
            },
            methods:{
                showNotesEditor: function(){
                    this.showEditor = !this.showEditor;
                },
                loadDetail: function (index) {
                    console.log(index);
                    console.log(this.course.id);
                    console.log(this.teacher.id);
                },
                addMaterial: function (index) {
                    this.resetMaterialForm(index);
                    this.showMaterialForm = true;
                },
                submitUpload: function(){
                    if(Util.isEmpty(this.courseMaterialModel.index)){
                        this.$message.error('请指定课件将用于第几节课');
                        return false;
                    }
                    if(Util.isEmpty(this.courseMaterialModel.type)){
                        this.$message.error('请指定课件的类型');
                        return false;
                    }
                    if(Util.isEmpty(this.courseMaterialModel.description)){
                        this.$message.error('请描述一下本课件的用途');
                        return false;
                    }
                    if(Util.isEmpty(this.selectedFile)){
                        this.$confirm('本课件没有关联任何文件, 是否继续?', '提示', {
                            confirmButtonText: '确定. 不需要关联任何文件',
                            cancelButtonText: '取消',
                            type: 'warning'
                        }).then(() => {
                            // 继续上传即可
                            this._startSaving();
                        }).catch(() => {
                            this.$message({
                                type: 'info',
                                message: '已取消保存操作'
                            });
                            return false;
                        });
                    }
                    else{
                        this.courseMaterialModel.url = this.selectedFile.url;
                        this.courseMaterialModel.media_id = this.selectedFile.id;
                        this._startSaving();
                    }
                },
                _startSaving: function(){
                    saveMaterial(this.courseMaterialModel).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                type:'success',
                                message:'保存成功'
                            });
                            window.location.reload();
                        }
                        else{
                            this.$message.error('保存失败. ' + res.data.data);
                        }
                    })
                },
                resetMaterialForm: function(index){
                    this.courseMaterialModel.id = null;
                    this.courseMaterialModel.index = index;
                    this.courseMaterialModel.type = null;
                    this.courseMaterialModel.description = null;
                    this.courseMaterialModel.url = null;
                    this.courseMaterialModel.media_id = 0;
                    this.selectedFile = null;
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