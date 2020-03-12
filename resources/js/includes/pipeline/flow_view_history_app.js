/**
 * 查看一个流程
 */
import { processAction, viewApplicationByAction } from "../../common/flow";
import { Constants } from "../../common/constants";
import { Util } from "../../common/utils";

if (document.getElementById('pipeline-flow-view-history-app')) {
    new Vue({
        el: '#pipeline-flow-view-history-app',
        data() {
            return {
                activities: ['办公室','校长室','还有哪里'],

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
        methods: {
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