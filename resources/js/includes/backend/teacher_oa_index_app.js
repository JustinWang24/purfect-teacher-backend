/**
 * 教师办公 app
 */
import { Util } from "../../common/utils";
import { startedByMe, waitingForMe, cancelApplicationByUser, waitingByMe, processedByMe, copyByMe } from "../../common/flow";
import Axios from "axios";
import FlowForm from './auto-flow/flow-form'

if (document.getElementById('teacher-oa-index-app')) {
    new Vue({
        el: '#teacher-oa-index-app',
        components: {
            FlowForm
        },
        data() {
            return {
                isLoading: true,
                schoolId: null,
                position: 1,
                iconList: [], // 上边icon
                myflows: [], // 左边我的审批
                open: -1, // 当前展开的我的审批
                show: 0, // showtab 右边tab切换
                nav: [
                    { tit: "待审批" },
                    { tit: "已审批" },
                    { tit: "我发起的" },
                    { tit: "我抄送的" }
                ], // 右边nav
                page: 1, // 当前页
                size: 10, // 条数
                total: '', // 总条数
                keyword: '', // 关键字
                statusMap: {
                    0: '审核中',
                    1: '已通过',
                    2: '未通过'
                }, // 审批状态
                tableData: [], // 审批列表
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.getofficeIcon();
            this.loadFlowsWaitingByMe();
            this.myFlows();
        },
        methods: {
            // 获取头部icon
            getofficeIcon() {
                axios.post(
                    '/api/office/office-page',
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.iconList = res.data.data;
                    }
                })
            },
            // 获取--我的审批
            myFlows() {
                axios.post("/api/pipeline/flows/my").then(res => {
                    this.myflows = res.data.data.types;
                }).catch(err => {
                    console.log(err)
                })
            },
            // 展示--收起
            close(id) {
                this.open = id
            },
            // 每个申请的点击事件
            goCreateFlow(flow) {
                this.$refs.flowForm.init(flow)
            },
            // tab切换
            list_click(tab) {
                this.show = tab;
                this.keyword = ''
                if (tab === 0) {
                    this.loadFlowsWaitingByMe();
                } else if (tab === 1) {
                    this.loadFlowsProcessedByMe();
                } else if (tab === 2) {
                    this.loadFlowsStartedByMe();
                } else {
                    this.loadFlowsCopyByMe();
                }
            },
            // 搜索
            serach() {
                if (this.show === 0) {
                    this.loadFlowsWaitingByMe();
                } else if (this.show === 1) {
                    this.loadFlowsProcessedByMe();
                } else if (this.show === 2) {
                    this.loadFlowsStartedByMe();
                } else {
                    this.loadFlowsCopyByMe();
                }
            },
            // 分页
            handleCurrentChange(val) {
                this.page = val;
                if (this.show === 0) {
                    this.loadFlowsWaitingByMe();
                } else if (this.show === 1) {
                    this.loadFlowsProcessedByMe();
                } else if (this.show === 2) {
                    this.loadFlowsStartedByMe();
                } else {
                    this.loadFlowsCopyByMe();
                }
            },
            // list展示
            // waiting_for_me=待我审批
            loadFlowsWaitingByMe: function () {
                this.isLoading = true;
                waitingByMe(this.userUuid, this.keyword, this.position, this.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.tableData = res.data.data.flows;
                        this.total = res.data.data.total;
                    }
                    this.isLoading = false;
                });
            },
            // my_processed=我审批的
            loadFlowsProcessedByMe: function () {
                this.isLoading = true;
                processedByMe(this.userUuid, this.keyword, this.position, this.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.tableData = res.data.data.flows;
                        this.total = res.data.data.total;
                    }
                    this.isLoading = false;
                });
            },
            // in_progress=我发起的
            loadFlowsStartedByMe: function () {
                this.isLoading = true;
                startedByMe(this.userUuid, this.keyword, this.position, this.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.tableData = res.data.data.flows;
                        this.total = res.data.data.total;
                    }
                    this.isLoading = false;
                });
            },
            // copy_to_me=抄送我的
            loadFlowsCopyByMe: function () {
                this.isLoading = true;
                copyByMe(this.userUuid, this.keyword, this.position, this.page, this.size).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.tableData = res.data.data.flows;
                        this.total = res.data.data.total;
                    }
                    this.isLoading = false;
                });
            },

            // startFlow: function (flowId) {
            //     const url = this.url.flowOpen + '?flow=' + flowId + '&uuid=' + this.userUuid;
            //     window.open(url, '_blank');
            // },
            // viewApplicationDetail: function (action) {
            //     window.location.href = '/pipeline/flow/view-history?action_id=' + action.id;
            // },
            // reloadThisPage: function () {
            //     Util.reloadCurrentPage(this);
            // }
        }
    });
}