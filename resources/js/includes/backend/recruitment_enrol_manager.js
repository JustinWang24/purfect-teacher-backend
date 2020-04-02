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
                // 分班form表单
                classForm: {
                  note: '', // 描述
                  planId: 0, // 招生Id
                  formId: 0, // 申请Id
                  classId: '', // 分班选择值
                },
                currentName: '',
                showNoteFormFlag: false,
                rejectNoteFormFlag: false,
                showClassFormFlag: false,
                classOptionList: [], // 班级列表
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
            },
            // 分班操作
            btnClassFormFlag: function(planId,formId){
              this.showClassFormFlag = true;
              this.classForm.planId = planId;
              this.classForm.formId = formId;
              this.getClassList(planId);
            },
            // 获取分班信息
            getClassList: function(planId){
              axios.post(
                Constants.API.REGISTRATION_FORM.GET_CLASS_LIST,
                {planId:planId,uuid: this.userUuid}
              ).then(res => {
                if(Util.isAjaxResOk(res)){
                  this.classOptionList = [];
                  if(res.data.data.length > 0) {
                    for ( var i=0; i < res.data.data.length; i++ ){
                      this.classOptionList.unshift(
                        {value:res.data.data[i].id,label:res.data.data[i].name+"("+res.data.data[i].count+"人)"}
                      );
                    }
                  }
                  console.log(this.classOptionList)
                }else{
                 /* this.$notify.error({
                    title: '错误',
                    message: res.data.message
                  });*/
                }
              })
            },
            // 保存分班信息
            classSubmit: function(){
              // 请选择班级
              if(!this.classForm.classId) {
                this.$notify.error({
                  title: '温馨提示',
                  message: '请选择班级'
                });
                return;
              }
              axios.post(
                Constants.API.REGISTRATION_FORM.SAVE_CLASS_INFO,
                {form: this.classForm, uuid: this.userUuid}
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
            },
          }
    });
}
