// 后台录取学生的管理程序
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

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