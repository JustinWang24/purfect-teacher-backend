/**
 * 学生首页 app
 */
import { Constants } from "../../common/constants";
import { Util } from "../../common/utils";
import { startedByMe, cancelApplicationByUser, waitingByMe, processedByMe, copyByMe } from "../../common/flow";
if (document.getElementById('student-homepage-app')) {
    new Vue({
        el: '#student-homepage-app',
        data() {
            return {
                schoolId: null,
                userUuid: null,
                url: {
                    flowOpen: '',
                },
                isLoading: true,
                showStarted: false,
                flowsStartedByMe: [],
                started: {
                    page: 1,
                    loading: false, // 加载中
                    finished: false // 没有更多
                },
                showWaiting: false,
                waitingList: [],
                waiting: {
                    page: 1,
                    loading: false, // 加载中
                    finished: false // 没有更多
                },
                showProcessed: false,
                processedList: [],
                processed: {
                    page: 1,
                    loading: false, // 加载中
                    finished: false // 没有更多
                },
                showCopy: false,
                copyList: [],
                copy: {
                    page: 1,
                    loading: false, // 加载中
                    finished: false // 没有更多
                },
                size: 20,
                keyword: '',
                position: '',
                apiToken: null,
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.position = dom.dataset.position;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            this.apiToken = dom.dataset.apitoken;
            this.loadFlowsStartedByMe();
            this.loadFlowsWaitingByMe();
            this.loadFlowsProcessedByMe();
            this.loadFlowsCopyByMe();
        },
        methods: {
            // in_progress=我发起的
            onLoad1() {
                this.started.page++;
                this.loadFlowsStartedByMe();
            },
            loadFlowsStartedByMe: function () {
                startedByMe(this.userUuid, this.keyword, this.position, this.started.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.isLoading = false;
                        if (res.data.data.flows.length > 0) {
                            this.flowsStartedByMe = this.flowsStartedByMe.concat(res.data.data.flows)
                            this.showStarted = false;
                            this.started.loading = false;
                            if (this.flowsStartedByMe.length >= res.data.data.total) {
                                this.started.finished = true;
                            }
                        } else {
                            this.flowsStartedByMe = [];
                            this.showStarted = true;
                            this.started.finished = true;
                        }
                    }
                });
            },
            // waiting_for_me=待我审批
            onLoad2() {
                this.waiting.page++;
                this.loadFlowsWaitingByMe();
            },
            // waiting_for_me=待我审批
            loadFlowsWaitingByMe: function () {
                this.isLoading = false;
                waitingByMe(this.userUuid, this.keyword, this.position, this.waiting.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        if (res.data.data.flows.length > 0) {
                            this.waitingList = this.waitingList.concat(res.data.data.flows)
                            this.showWaiting = false;
                            this.waiting.loading = false;
                            if (this.waitingList.length >= res.data.data.total) {
                                this.waiting.finished = true;
                            }
                        } else {
                            this.waitingList = [];
                            this.showWaiting = true;
                            this.waiting.finished = true;
                        }
                    }
                });
            },
            // my_processed=我审批的
            onLoad3() {
                this.processed.page++;
                this.loadFlowsProcessedByMe();
            },
            loadFlowsProcessedByMe: function () {
                processedByMe(this.userUuid, this.keyword, this.position, this.processed.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.isLoading = false;
                        if (res.data.data.flows.length > 0) {
                            this.processedList = this.processedList.concat(res.data.data.flows)
                            this.showProcessed = false;
                            this.processed.loading = false;
                            if (this.processedList.length >= res.data.data.total) {
                                this.processed.finished = true;
                            }
                        } else {
                            this.processedList = [];
                            this.showProcessed = true;
                            this.processed.loading = true;
                        }
                    }
                });
            },
            // copy_to_me=抄送我的
            onLoad4() {
                this.copy.page++;
                tthis.loadFlowsCopyByMe();
            },
            loadFlowsCopyByMe: function () {
                copyByMe(this.userUuid, this.keyword, this.position, this.copy.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.isLoading = false;
                        if (res.data.data.flows.length > 0) {
                            this.copyList = this.copyList.concat(res.data.data.flows)
                            this.showCopy = false;
                            this.copy.loading = false;
                            if (this.copyList.length >= res.data.data.total) {
                                this.copy.finished = true;
                            }
                        } else {
                            this.copyList = [];
                            this.showCopy = true;
                            this.copy.loading = true;
                        }
                    }
                });
            },
            into() {
                console.log(1)
            },
            // 学生查看自己的申请详情
            viewMyApplication: function (userFlow) {
                this.into();
                let url = '/pipeline/flow/view-history?user_flow_id=' + userFlow.id;
                if (!Util.isEmpty(this.apiToken)) {
                    url = '/h5/flow/user/view-history?user_flow_id=' + userFlow.id + '&api_token=' + this.apiToken;
                }
                window.location.href = url;
            },
            startFlow: function (flowId) {
                window.location.href = this.url.flowOpen + '?flow=' + flowId + '&uuid=' + this.userUuid;
            },



            // 取消一个申请
            cancelMyApplication: function (userFlow) {
                this.$confirm('您确认撤销此申请吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning',
                    center: true
                }).then(() => {
                    cancelApplicationByUser(userFlow.id).then(res => {
                        if (Util.isAjaxResOk(res)) {
                            const idx = Util.GetItemIndexById(userFlow.id, this.flowsStartedByMe);
                            this.flowsStartedByMe.splice(idx, 1);
                            this.$message({
                                type: 'success',
                                message: '撤销申请操作成功!'
                            })
                        }
                        else {
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
            reloadThisPage: function () {
                Util.reloadCurrentPage(this);
            },
            flowResultText: function (done) {
                if (done === Constants.FLOW_FINAL_RESULT.PENDING) {
                    return Constants.FLOW_FINAL_RESULT.PENDING_TXT;
                }
                else if (done === Constants.FLOW_FINAL_RESULT.DONE) {
                    return Constants.FLOW_FINAL_RESULT.DONE_TXT;
                }
                else if (done === Constants.FLOW_FINAL_RESULT.REJECTED) {
                    return Constants.FLOW_FINAL_RESULT.REJECTED_TXT;
                }
            },
            flowResultClass: function (done) {
                if (done === Constants.FLOW_FINAL_RESULT.PENDING) {
                    return Constants.FLOW_FINAL_RESULT.PENDING_CLASS;
                }
                else if (done === Constants.FLOW_FINAL_RESULT.DONE) {
                    return Constants.FLOW_FINAL_RESULT.DONE_CLASS;
                }
                else if (done === Constants.FLOW_FINAL_RESULT.REJECTED) {
                    return Constants.FLOW_FINAL_RESULT.REJECTED_CLASS;
                }
            }
        }
    });
}