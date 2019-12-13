/**
 * 申请类型
 */
import {Util} from "../../common/utils";

if(document.getElementById('application-type-app')){
    new Vue({
        el:'#application-type-app',
        data(){
            return {
                type:{
                    name:'',
                    media_id:''
                },
                showFileManagerFlag: false,
                selectedImgUrl: null,
            }
        },
        methods:{
            onSubmit: function(){
                axios.post(
                    '/school_manager/students/applications-set-save',{
                        type: this.type
                    }
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            type: 'success',
                            message: '保存成功!'
                        });
                        window.location.href = '/school_manager/students/applications-set';
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })
            },
            pickFileHandler: function(payload){
                this.type.media_id = payload.file.id;
                this.selectedImgUrl = payload.file.url;
                this.showFileManagerFlag = false;
            },
        }
    })
}