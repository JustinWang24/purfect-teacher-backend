// 后台的管理招生计划的报名表的管理程序
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

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