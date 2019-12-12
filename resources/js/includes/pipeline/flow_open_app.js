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
                },
                showFileManagerFlag: false,
                isLoading: false,
                done: false,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.action.node_id = dom.dataset.nodeid;
            this.action.flow_id = dom.dataset.flowid;
        },
        methods:{
            closeWindow: function(){
                window.opener = null;
                window.open('','_self');
                window.close();
            },
            onStartActionSubmit: function () {
                this.isLoading = true;
                start(this.action).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.action.id = res.data.data.id;
                        this.$message({type:'success',message: '提交成功'});
                        window.location.href = '/home';
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