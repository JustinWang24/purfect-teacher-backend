/**
 * 学生首页 app
 */
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";
import { startedByMe, cancelApplicationByUser } from "../../common/flow";

if(document.getElementById('student-homepage-app')){
    new Vue({
        el:'#student-homepage-app',
        data(){
            return {
                schoolId: null,
                userUuid: null,
                url:{
                    flowOpen: '',
                },
                isLoading: false,
                flowsStartedByMe:[],
                apiToken: null,
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            this.apiToken = dom.dataset.apitoken;
            this.loadFlowsStartedByMe();
        },
        methods:{
            startFlow: function(flowId){
                window.location.href = this.url.flowOpen + '?flow=' + flowId + '&uuid=' + this.userUuid;
            },
            loadFlowsStartedByMe: function(){
                this.isLoading = true;
                startedByMe(this.userUuid).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.flowsStartedByMe = res.data.data.flows;
                    }
                    this.isLoading = false;
                });
            },
            // 取消一个申请
            cancelMyApplication: function(userFlow){
                this.$confirm('您确认撤销此申请吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning',
                    center: true
                }).then(() => {
                    cancelApplicationByUser(userFlow.id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            const idx = Util.GetItemIndexById(userFlow.id, this.flowsStartedByMe);
                            this.flowsStartedByMe.splice(idx, 1);
                        }
                        else{
                            this.$message.error(res.data.message);
                        }
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '操作取消'
                    });
                });
            },
            // 学生查看自己的申请详情
            viewMyApplication: function(userFlow){
                let url = '/pipeline/flow/view-history?user_flow_id=' + userFlow.id;
                if(!Util.isEmpty(this.apiToken)){
                    url = '/h5/flow/user/view-history?user_flow_id=' + userFlow.id + '&api_token=' + this.apiToken;
                }
                window.location.href = url;
            },
            reloadThisPage: function(){
                Util.reloadCurrentPage(this);
            },
            flowResultText: function(done){
                if(done === Constants.FLOW_FINAL_RESULT.PENDING){
                    return Constants.FLOW_FINAL_RESULT.PENDING_TXT;
                }
                else if(done === Constants.FLOW_FINAL_RESULT.DONE){
                    return Constants.FLOW_FINAL_RESULT.DONE_TXT;
                }
                else if(done === Constants.FLOW_FINAL_RESULT.REJECTED){
                    return Constants.FLOW_FINAL_RESULT.REJECTED_TXT;
                }
            },
            flowResultClass: function(done){
                if(done === Constants.FLOW_FINAL_RESULT.PENDING){
                    return Constants.FLOW_FINAL_RESULT.PENDING_CLASS;
                }
                else if(done === Constants.FLOW_FINAL_RESULT.DONE){
                    return Constants.FLOW_FINAL_RESULT.DONE_CLASS;
                }
                else if(done === Constants.FLOW_FINAL_RESULT.REJECTED){
                    return Constants.FLOW_FINAL_RESULT.REJECTED_CLASS;
                }
            }
        }
    });
}