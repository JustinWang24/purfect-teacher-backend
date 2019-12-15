/**
 * Open一个流程
 */
import {Util} from "../../common/utils";
import {start} from "../../common/flow";

if(document.getElementById('pipeline-flow-open-app')){
    new Vue({
        el:'#pipeline-flow-open-app',
        data(){
            return {
                schoolId: null,
                action:{
                    id:null,
                    flow_id:'',
                    node_id:'',
                    content:'',
                    attachments:[],
                    urgent: false,
                    options:[]
                },
                showFileManagerFlag: false,
                isLoading: false,
                done: false,
                appRequest: undefined,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.action.node_id = dom.dataset.nodeid;
            this.action.flow_id = dom.dataset.flowid;
            this.appRequest = !Util.isEmpty(dom.dataset.apprequest);

            this.action.options = JSON.parse(dom.dataset.nodeoptions);
        },
        methods:{
            closeWindow: function(){
                window.opener = null;
                window.open('','_self');
                window.close();
            },
            onStartActionSubmit: function () {
                // 验证
                let missingOption = false;

                for(let i=0;i<this.action.options.length;i++){
                    if(Util.isEmpty(this.action.options[i].value)){
                        missingOption = '请填写' + this.action.options[i].name;
                        break;
                    }
                }


                if(missingOption){
                    this.$message.error(missingOption);
                    return;
                }

                if(Util.isEmpty(this.action.content)){
                    this.$message.error('请填写原因说明文字');
                    return;
                }

                this.isLoading = true;

                start(this.action, this.appRequest).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.action.id = res.data.data.id;
                        this.$message({type:'success',message: '提交成功'});
                        if(!this.appRequest){
                            window.location.href = '/home'; // Web 应用
                        }
                        else{
                            window.location.href = res.data.data.url; // 前端 APP 调用, 应该跳转到学生的申请列表
                        }
                    }
                    else{
                        this.$message.error(res.data.message);
                        this.done = false;
                    }
                    this.isLoading = false;
                })
            },
            pickFileHandler: function(payload){
                this.showFileManagerFlag = false;
                const attachment = {
                    id:null,
                    action_id: null,
                    media_id: payload.file.id,
                    url: payload.file.url,
                    file_name: payload.file.file_name
                };
                this.action.attachments.push(attachment);
            },
            dropAttachment: function(idx, attachment){
                this.action.attachments.splice(idx, 1);
                this.$message({type:'info', message: '移除文件: ' + attachment.file_name});
            }
        }
    });
}