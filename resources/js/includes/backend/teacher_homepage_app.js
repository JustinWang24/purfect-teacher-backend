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
                calendar: new Date(), // 校历
                schooleventsList: [], // 获取首页校园安排
                schoolalleventsList: [], // 获取首页历史安排
                attendanceList: [], // 获取首页值周内容
                dataLength: 0,
                num: 4,
                loading: false,
                drawer: false, // 全部历史安排
                showNewInfo: false, // 校园新闻详情侧边栏
                notice: {
                    title: "",
                    created_at: "",
                    sections: "",
                    image: "",
                    type: ""
                } // 校园新闻详情
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
                    { posit: 21 }
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
                        this.newsList = res.data.data.list;
                    }
                })
            },
            // 校园新闻详情
            newInfo(id) {
                axios.post("/api/home/news-info", {
                    id: id
                }).then(res => {
                    this.showNewInfo = true
                    this.notice = res.data.data
                    console.log(this.notice)
                }).catch(err => {
                    console.log(err)
                })
            },
            // 获取首页校历
            getCalendar() {
                axios.post(
                    '/api/school/calendar'
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        // console.log(res)
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
                        this.schooleventsList = res.data.data.events.slice(0, 6)
                        this.schoolalleventsList = res.data.data.events
                    }
                })
            },
            // 全部历史安排和校园新闻详情关闭按钮
            handleClose(done) {
                done()
            },
            // 获取首页值周内容
            getAttendanceList(page = 1) {
                // alert(page)
                axios.post(
                    '/api/attendance/list',
                    { page: page }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        // this.attendanceList2 = res.data.data.data.slice(0, 4)
                        this.attendanceList = res.data.data.data;
                        // this.dataLength = res.data.data.data.length;
                        // alert(this.dataLength)/
                    }
                })
            },
            load() {
                console.log(111)
                // alert("222");
                // this.loading = true
                // setTimeout(() => {
                // this.num += 2;
                // this.loading = false
                //   }, 2000)
                // this.getAttendanceList(1);
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
        },
        //     computed: {
        //         attendanceList2() {
        //             return this.attendanceList.slice(0, this.num)
        //         },
        //         noMore() {
        //             return this.num >= this.dataLength;
        //         },
        //     },
    });
}