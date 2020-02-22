/**
 * 教师办公 app
 */
import { Util } from "../../common/utils";
import { startedByMe, waitingForMe } from "../../common/flow";
import Axios from "axios";

if (document.getElementById('teacher-oa-logs-app')) {
    new Vue({
        el: '#teacher-oa-logs-app',
        data() {
            return {
                schoolId: null,
                userUuid: null,
                url: {
                    flowOpen: ''
                },
                isLoading: false,
                flowsStartedByMe: [],
                flowsWaitingForMe: [],
                nav: [
                    { tit: "未发送", type: 3 },
                    { tit: "已发送", type: 2 },
                    { tit: "已接收", type: 1 },
                ],
                show: 3,
                drawer: false, // 侧边栏
                log: {
                    title: "",
                    content: ""
                },
                keyword: "",
                logList: [],
                check: false,
                btnText: "全选"
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            this.loadFlowsStartedByMe();
            this.loadFlowsWaitingForMe();
            this.getlogList(3);
        },
        watch: {
            logList: {
                deep: true,
                handler(val) {
                    if (val.every(item => item.sele)) {
                        this.check = true;
                        this.btnText = "取消全选"
                    } else {
                        this.check = false;
                        this.btnText = "全选"
                    }
                }
            }
        },
        methods: {
            handleCheckAllChange() {
                this.check = !this.check;
                if (this.check) {
                    this.logList = this.logList.map(item => {
                        item.sele = true;
                        return item;
                    });
                    this.btnText = "取消全选"
                    console.log(this.logList)
                } else {
                    this.logList = this.logList.map(item => {
                        item.sele = false;
                        return item;
                    });
                    this.btnText = "全选";
                    console.log(this.logList)
                }
            },
            // tab切换
            list_click(tab) {
                this.show = tab;
                this.getlogList(tab);
            },
            // 获取日志列表
            getlogList(tab) {
                axios.post(
                    '/api/Oa/list-work-log',
                    { page: 1, type: tab, keyword: this.keyword }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.logList = res.data.data.map((item, index) => {
                            item.sele = false;
                            return item;
                        });
                    }
                })
            },
            // 添加日志
            addlog() {
                if (this.log.title !== '' || this.log.content !== '') {
                    axios.post(
                        '/api/Oa/add-work-log',
                        { title: this.log.title, content: this.log.content }
                    ).then(res => {
                        if (Util.isAjaxResOk(res)) {
                            this.$message({
                                message: res.data.message,
                                type: 'success'
                            });
                            this.drawer = false
                        }
                    })
                } else {
                    this.$message({
                        message: "标题和内容不得为空",
                        type: 'error'
                    });
                }
            },
            // 添加日志---关闭按钮
            handleClose(done) {
                done()
            },
            startFlow: function (flowId) {
                const url = this.url.flowOpen + '?flow=' + flowId + '&uuid=' + this.userUuid;
                window.open(url, '_blank');
            },
            loadFlowsStartedByMe: function () {
                this.isLoading = true;
                startedByMe(this.userUuid).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.flowsStartedByMe = res.data.data.actions;
                    }
                    this.isLoading = false;
                });
            },
            loadFlowsWaitingForMe: function () {
                waitingForMe(this.userUuid).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.flowsWaitingForMe = res.data.data.actions;
                    }
                });
            },
            viewApplicationDetail: function (action) {
                window.location.href = '/pipeline/flow/view-history?action_id=' + action.id;
            },
            reloadThisPage: function () {
                Util.reloadCurrentPage(this);
            },

        }
    });
}