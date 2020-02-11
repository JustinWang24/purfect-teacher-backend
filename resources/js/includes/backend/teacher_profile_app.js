import {Util} from "../../common/utils";

if(document.getElementById('teacher-profile-app-wrap')){
    new Vue({
        el:'#teacher-profile-app-wrap',
        data(){
            return {
                qualification:{
                    id:null,
                    title:null,
                    desc:null,
                    type:null,
                    year:null,
                    user_id:null,
                    path: null,
                    uploaded_by: null,
                    media_id: null,
                    file_name:null,
                },
                showFormFlag: false,
                showFileManagerFlag: false,
                types:[],
                qualifications:[]
            }
        },
        created(){
            this.types = Util.getTeacherQualificationTypes();
            const dom = document.getElementById('app-init-data-holder');
            this.qualification.user_id = dom.dataset.user;
            this.loadQualifications();
        },
        methods: {
            resetForm: function(){
                this.qualification.id = null;
                this.qualification.title = null;
                this.qualification.desc = null;
                this.qualification.type = null;
                this.qualification.year = null;
                this.qualification.path = null;
                this.qualification.uploaded_by = null;
                this.qualification.media_id = null;
                this.qualification.file_name = null;
            },
            showForm: function (teacherId) {
                this.showFormFlag = true;
                this.qualification.user_id = teacherId;
            },
            onSubmit: function(){
                axios.post(
                    '/api/teacher/evaluation/save-qualification',
                    {qualification: this.qualification}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type: 'success',
                            message: '保存成功'
                        });
                        this.showFormFlag = false;
                        this.resetForm();
                        this.loadQualifications();
                    }
                });
            },
            loadQualifications: function(){
                axios.post(
                    '/api/teacher/evaluation/load-qualifications',
                    {user: this.qualification.user_id}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.qualifications = res.data.data.qualifications;
                    }
                });
            },
            pickFileHandler: function (payload) {
                this.qualification.path = payload.file.url;
                this.qualification.file_name = payload.file.file_name;
                this.qualification.media_id = payload.file.id;
                this.showFileManagerFlag = false;
            }
        }
    })
}