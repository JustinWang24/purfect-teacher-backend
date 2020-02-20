/**
 * 教师首页 app
 */
import { Util } from "../../common/utils";
import { startedByMe, waitingForMe } from "../../common/flow";

if (document.getElementById('teacher-homepage-app')) {
    new Vue({
        el: '#teacher-homepage-app',
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
                bannerList: [], // 获取首页banner
                newsList: [], // 获取首页校园新闻
                schoolalleventsList: [], // 获取首页校园安排
                attendanceList: [] // 获取首页值周内容
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            this.loadFlowsStartedByMe();
            this.loadFlowsWaitingForMe();
            this.getBanner(); // 获取首页banner
            this.getnewsPage(); // 获取首页校园新闻
            this.getCalendar(); // 获取首页校历
            this.getAllevents(); // 获取首页校园安排
            this.getAttendanceList(); // 获取首页值周内容
        },
        methods: {
            // 获取首页banner
            getBanner() {
                axios.post(
                    '/api/banner/getBanner',
                    { posit: 12 }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.bannerList = res.data.data
                    }
                })
            },
            // 获取首页校园新闻
            getnewsPage() {
                axios.post(
                    '/api/home/newsPage',
                    { page: 1 }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.newsList = res.data.data.list.slice(0, 5)
                    }
                })
            },
            // 获取首页校历
            getCalendar() {
                axios.post(
                    '/api/school/calendar'
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        console.log(res)
                        // this.schoolalleventsList = res.data.data.events.slice(0,5)
                    }
                })
            },
            // 获取首页校园安排
            getAllevents() {
                axios.post(
                    '/api/school/all-events'
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.schoolalleventsList = res.data.data.events.slice(0, 6)
                    }
                })
            },
            // 获取首页值周内容
            getAttendanceList() {
                axios.post(
                    '/api/attendance/list'
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.attendanceList = res.data.data.data.slice(0, 4)
                    }
                })
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