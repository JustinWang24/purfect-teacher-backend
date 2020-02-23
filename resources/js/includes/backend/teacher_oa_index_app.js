/**
 * 教师办公 app
 */
import { Util } from "../../common/utils";
import { startedByMe, waitingForMe } from "../../common/flow";
import Axios from "axios";

if (document.getElementById('teacher-oa-index-app')) {
    new Vue({
        el: '#teacher-oa-index-app',
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
                    { tit: "待审批" },
                    { tit: "已审批" },
                    { tit: "我发起的" },
                    { tit: "我抄送的" }
                ],
                show: 0
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            this.loadFlowsStartedByMe();
            this.loadFlowsWaitingForMe();

            this.getofficeIcon();
        },
        methods: {
            // 获取头部icon
            getofficeIcon() {
                axios.post(
                    '/api/office/office-page',
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        console.log(res)
                    }
                })
            },
            // tab切换
            list_click(tab) {
                this.show = tab;
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
            }
        }
    });
}