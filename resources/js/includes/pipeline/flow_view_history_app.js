/**
 * 查看一个流程
 */
import { processAction, viewApplicationByAction } from "../../common/flow";
import { Constants } from "../../common/constants";
import { Util } from "../../common/utils";
import { Dialog } from 'element-ui';
import Axios from "axios";
Vue.use(Dialog);
if (document.getElementById('pipeline-flow-view-history-app')) {
    new Vue({
        el: '#pipeline-flow-view-history-app',
        data() {
            return {
                textarea: "", // 审批意见
                dialogVisible: false, // 审批
                tips: false, // 撤回
                action1: {
                    id: null, //action.id
                    result: "3", //3=同意 5=驳回
                    content: "dddd", //审批意见
                    urgent: false //是否加急
                },
                userUuid: null,
                actionId: null,
                userFlowId: null,
                schoolId: null,
                action: {
                    content: '',
                    attachments: [],
                    result: null,
                    urgent: false,
                },
                showFileManagerFlag: false,
                isLoading: false,
                history: [],
                results: [
                    { id: Constants.FLOW_ACTION_RESULT.PENDING, label: Constants.FLOW_ACTION_RESULT.PENDING_TXT },
                    { id: Constants.FLOW_ACTION_RESULT.NOTICED, label: Constants.FLOW_ACTION_RESULT.NOTICED_TXT },
                    { id: Constants.FLOW_ACTION_RESULT.PASSED, label: Constants.FLOW_ACTION_RESULT.PASSED_TXT },
                    { id: Constants.FLOW_ACTION_RESULT.REJECTED, label: Constants.FLOW_ACTION_RESULT.REJECTED_TXT },
                ],
                userFlow: {}, // 服务器端返回的
                appRequest: false,  // 是否 App 嵌入的
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.appRequest = !Util.isEmpty(dom.dataset.apprequest);
            this.actionId = dom.dataset.actionid;
            this.userUuid = dom.dataset.useruuid;
            this.userFlowId = dom.dataset.flowid;
            if (this.actionId) {
                this.action = JSON.parse(dom.dataset.theaction);
                if (this.action.node.next_node === 0) {
                    this.results.push({
                        id: Constants.FLOW_ACTION_RESULT.TERMINATED,
                        label: Constants.FLOW_ACTION_RESULT.TERMINATED_TXT
                    });
                }
            }
            // this.loadWholeFlow();
        },
        computed: {
            countLength() {
                return this.textarea.length >= 100;
            }
        },
        methods: {
            // 审批按钮
            button(result) {
                if (result === 5 && this.textarea === '') {
                    this.$message.info('审批意见不能为空');
                    return;
                } else {
                    this.action1 = {
                        id: this.actionId, //action.id
                        result: result, //3=同意 5=驳回
                        content: this.textarea, //审批意见
                        urgent: false //是否加急
                    }
                    axios.post('/api/pipeline/flow/process', { action: this.action1 }
                    ).then(res => {
                        this.dialogVisible = false;
                        window.history.back();
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },
            // 撤销
            cancelAction() {
                axios.post('/api/pipeline/flow/cancel-action', { user_flow_id: this.userFlowId }
                ).then(res => {
                    this.tips = false;
                    window.history.back();
                }).catch(err => {
                    console.log(err);
                })
            },
            loadWholeFlow: function () {
                this.isLoading = true;
                viewApplicationByAction(this.actionId, this.userFlowId).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.userFlow = res.data.data.flow.userFlow;
                        this.history = res.data.data.flow.nodes;
                    }
                    else {
                        this.$message.error(res.data.message);
                    }
                    this.isLoading = false;
                }).catch(e => {
                    this.$message.error('系统繁忙!');
                })
            },
            onCreateActionSubmit: function () {
                if (this.action.result === Constants.FLOW_ACTION_RESULT.PENDING) {
                    this.$message.info('请给出您的审核意见');
                    return;
                }

                if (this.action.content.trim() === '') {
                    this.$message.info('请写下您的审核意见的说明文字');
                    return;
                }

                processAction(this.action).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.$message({
                            type: 'success',
                            message: '审核完毕'
                        });
                        window.location.href = '/home';
                    }
                    else {
                        this.$message.error(res.data.message);
                    }
                });
            },
            pickFileHandler: function (payload) {
                this.showFileManagerFlag = false;
                const attachment = {
                    id: null,
                    action_id: null,
                    media_id: payload.file.id,
                    url: payload.file.url,
                    file_name: payload.file.file_name
                };
                this.action.attachments.push(attachment);
            },
            getDotColor: function (node, currentNodeId, done) {
                let color = '#0bbd87';
                if (done === Constants.FLOW_FINAL_RESULT.PENDING) {
                    if (node.id !== currentNodeId) {
                        if (node.actions.length === 0) {
                            color = '#F2F6FC';
                        }
                    }
                    else {
                        color = '#409EFF';
                    }
                    return color;
                }
                else if (done === Constants.FLOW_FINAL_RESULT.REJECTED) {
                    color = '#F56C6C';
                }
                return color;
            }
        }
    });
}