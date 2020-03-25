/**
 * 教师办公 app
 */
import { Util } from "../../common/utils";
import { startedByMe, waitingForMe } from "../../common/flow";
import Axios from "axios";

if (document.getElementById('teacher-oa-notices-app')) {
    new Vue({
        el: '#teacher-oa-notices-app',
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
                oneList: [],
                twoList: [],
                threeList: [],
                detail: {},
                drawer: false, // 通知侧边栏
                titleName: "",
                attachments: [] // 附件
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            this.getnoticeList1();
            this.getnoticeList2();
            this.getnoticeList3();
        },
        methods: {
            // 获取本页列表
            getnoticeList1() {
                axios.post(
                    '/api/notice/notice-list',
                    { type: 1 }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.oneList = res.data.data.list
                    }
                })
            },
            // 获取本页列表
            getnoticeList2() {
                axios.post(
                    '/api/notice/notice-list',
                    { type: 2 }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.twoList = res.data.data.list
                    }
                })
            },
            // 获取本页列表
            getnoticeList3() {
                axios.post(
                    '/api/notice/notice-list',
                    { type: 3 }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.threeList = res.data.data.list
                    }
                })
            },
            // 获取详情
            oneDetail(id) {
                axios.post(
                    '/api/notice/notice-info',
                    { notice_id: id }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.drawer = true;
                        this.detail = res.data.data.notice
                        this.titleName = res.data.data.notice.type == 1 ? "通知" : (res.data.data.notice.type == 2 ? '公告' : '检查')
                        this.attachments = res.data.data.notice.attachments
                        console.log(res)
                    }
                })
            },
            // 关闭
            handleClose(done) {
                done();
                window.location.reload()
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