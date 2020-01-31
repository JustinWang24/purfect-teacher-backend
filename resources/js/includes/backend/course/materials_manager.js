// 学校的课程管理
import {Constants} from "../../../common/constants";
import {Util} from "../../../common/utils";

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
                    },
                    fileList:[],
                    fb: null,
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
                this.fb = new FormData();
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
                    this.fb = new FormData();
                    this.resetMaterialForm(index);
                    this.showMaterialForm = true;
                },
                uploadSectionFile: function(params){
                    console.log(params);
                },
                submitUpload: function(){
                    if(this.fb.has('material')){
                        this.fb.delete('material');
                    }
                    this.fb.append('material',JSON.stringify(this.courseMaterialModel));
                    axios.post(
                        '/teacher/course/materials/create',
                        this.fb,
                        {
                            headers: {"content-type": "multipart/form-data"}
                        }
                    ).then(res => {

                    })
                },
                beforeFileUpload: function(file){
                    console.log(file);
                    this.fb.append('file',file);
                    console.log(this.fb);
                    return false;
                },
                resetMaterialForm: function(index){
                    this.courseMaterialModel.id = null;
                    this.courseMaterialModel.index = index;
                    this.courseMaterialModel.type = null;
                    this.courseMaterialModel.description = null;
                    this.courseMaterialModel.url = null;
                }
            }
        });
    }
});