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

                bannerList: [], // 获取首页banner
                newsList: [], // 获取首页校园新闻
                showNewInfo: false, // 校园新闻详情侧边栏
                notice: { // 校园新闻详情
                    title: "",
                    created_at: "",
                    sections: "",
                    image: "",
                    type: ""
                },
                calendar: new Date(), // 校历
                yyyy: '', // 日历年份
                mm: '', // 日历月份
                dd: '',
                week: ['一', '二', '三', '四', '五', '六', '日'],
                tableWeek: [],
                tableDay: [],
                schooleventsList: [], // 获取首页校园安排
                schoolalleventsList: [], // 获取首页历史安排
                drawer: false, // 全部历史安排
                current_page: 1, // 值周第一页
                attendanceList: [], // 获取首页值周内容
                last_page: '', // 值周总页数 

                // num: 4,
                // isLoading: false,
                // flowsStartedByMe: [],
                // flowsWaitingForMe: [],
                // dataLength: 0,
                // loading: false,
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            // this.loadFlowsStartedByMe();
            // this.loadFlowsWaitingForMe();
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
                        this.yyyy = this.calendar.getFullYear();
                        this.mm = this.calendar.getMonth();
                        this.dd = this.calendar.getDate();
                        this.tableWeek = res.data.data.weeks;
                        let arr = res.data.data.days;
                        const times = Math.ceil(1000 / 7)
                        for (let i = 0; i <= times; i++) {
                            if (i * 7 >= arr.length) {
                                break
                            }
                            this.tableDay.push(arr.slice(i * 7, (i + 1) * 7))
                        }
                    }
                })
            },
            // 上一个月
            prevMonth() {
                console.log(this.mm)
            },
            // 下一个月
            nextMonth() {
                console.log(this.yyyy)
            },
            // 获取首页校园安排
            getAllevents() {
                axios.post(
                    '/api/school/all-events'
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.schooleventsList = res.data.data.events.reverse().slice(0, 6)
                        this.schoolalleventsList = res.data.data.events
                    }
                })
            },
            // 全部历史安排和校园新闻详情关闭按钮
            handleClose(done) {
                done()
            },
            // 获取首页值周内容
            getAttendanceList() {
                axios.post(
                    '/api/attendance/list',
                    { page: this.current_page }
                ).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.attendanceList = res.data.data.data;
                        this.last_page = res.data.data.last_page; // 总页数
                        this.current_page = res.data.data.current_page; // 当前页数
                    }
                })
            },
            // 滚动事件
            handleScroll() {
                let scrollTop = this.$refs.scrollTopList.scrollTop, // 2772.800048828125
                    clientHeight = this.$refs.scrollTopList.clientHeight, // 675
                    scrollHeight = this.$refs.scrollTopList.scrollHeight, // 3448
                    height = 675; //根据项目实际定义
                if (scrollTop + clientHeight >= scrollHeight - height) {
                    if (Number(this.current_page) >= Number(this.last_page)) {
                        return false
                    } else {
                        this.current_page = this.current_page + 1  //显示条数新增
                        this.getAttendanceList() //请求列表list 接口方法
                    }
                } else {
                    return false
                }
            },
            // 节流函数
            throttle(func, wait) {
                let lastTime = null
                let timeout
                return () => {
                    let context = this;
                    let now = new Date();
                    let arg = arguments;
                    if (now - lastTime - wait > 0) {
                        if (timeout) {
                            clearTimeout(timeout)
                            timeout = null
                        }
                        func.apply(context, arg)
                        lastTime = now
                    } else if (!timeout) {
                        timeout = setTimeout(() => {
                            func.apply(context, arg)
                        }, wait)
                    }
                }
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
            startFlow: function (flowId) {
                const url = this.url.flowOpen + '?flow=' + flowId + '&uuid=' + this.userUuid;
                window.open(url, '_blank');
            },
            reloadThisPage: function () {
                Util.reloadCurrentPage(this);
            }
        },
        mounted() {
            // 值周-------加载下一页
            this.$refs.scrollTopList.addEventListener("scroll", this.throttle(this.handleScroll, 1000), true)
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