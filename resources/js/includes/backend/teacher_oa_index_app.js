/**
 * 教师办公 app
 */
import { Util } from "../../common/utils";
import { startedByMe, waitingForMe, cancelApplicationByUser, waitingByMe, processedByMe, copyByMe } from "../../common/flow";
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
                isLoading: true,
                flowsStartedByMe: [],
                waitingList: [],
                processedList: [],
                copyList: [],

                iconList: [],
                show: 0, // showtab
                nav: [
                    { tit: "待审批" },
                    { tit: "已审批" },
                    { tit: "我发起的" },
                    { tit: "我抄送的" }
                ],
                myflows: [{
                    "name": "学生专用",
                    "key": 1000,
                    "flows": [{
                        "id": -1,
                        "name": "招生",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/pipeline\/icon1@2x.png"
                    }, {
                        "id": -2,
                        "name": "迎新",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/pipeline\/icon2@2x.png"
                    }, {
                        "id": -3,
                        "name": "离校",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/pipeline\/icon3@2x.png"
                    }]
                }, {
                    "name": "日常申请",
                    "key": 201,
                    "flows": [{
                        "id": 1,
                        "name": "奖学金",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/node-icon@2x.png",
                        "type": 201
                    }]
                }, {
                    "name": "校园助手",
                    "key": 1000,
                    "flows": [{
                        "id": -4,
                        "name": "通讯录",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/pipeline\/icon13@2x.png"
                    }]
                }, {
                    "name": "学生专用",
                    "key": 1000,
                    "flows": [{
                        "id": -1,
                        "name": "招生",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/pipeline\/icon1@2x.png"
                    }, {
                        "id": -2,
                        "name": "迎新",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/pipeline\/icon2@2x.png"
                    }, {
                        "id": -3,
                        "name": "离校",
                        "icon": "http:\/\/t.ytx.com\/assets\/img\/pipeline\/icon3@2x.png"
                    }]
                }], // 我的审批
                activeNames: [],
                open: 0, // 当前展开的我的审批
                tableData: [{
                    iconState: 1,
                    state: '请假',
                    name: '王小虎',
                    date: '2016-05-02',
                    status: 0,
                }, {
                    iconState: 0,
                    state: '休学',
                    name: '王小虎',
                    date: '2016-05-02',
                    status: 1,
                }, {
                    iconState: 0,
                    state: '休学',
                    name: '王小虎',
                    date: '2016-05-02',
                    status: 2,
                }, {
                    iconState: 1,
                    state: '请假',
                    name: '王小虎',
                    date: '2016-05-02',
                    status: 3,
                }, {
                    iconState: 1,
                    state: '请假',
                    name: '王小虎',
                    date: '2016-05-02',
                    status: 5,
                }]
            }
        },
        created() {
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.userUuid = dom.dataset.useruuid;
            this.url.flowOpen = dom.dataset.flowopen;
            this.loadFlowsStartedByMe();
            this.loadFlowsWaitingByMe();
            this.loadFlowsProcessedByMe();
            this.loadFlowsCopyByMe();
            this.getofficeIcon();
            // this.myFlows();
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
            // tab切换
            list_click(tab) {
                this.show = tab;
            },
            // 获取--我的审批
            myFlows() {
                axios.post("/api/pipeline/flows/my").then(res => {
                    this.myflows = res.data.data;
                }).catch(err => {
                    console.log(err)
                })
            },
            // list展示
            // in_progress=我发起的
            loadFlowsStartedByMe: function () {
                this.isLoading = true;
                startedByMe(this.userUuid, this.keyword, this.position).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.flowsStartedByMe = res.data.data.flows;
                    }
                    this.isLoading = false;
                });
            },
            // waiting_for_me=待我审批
            loadFlowsWaitingByMe: function () {
                this.isLoading = true;
                waitingByMe(this.userUuid, this.keyword, this.position).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.waitingList = res.data.data.flows;
                    }
                    this.isLoading = false;
                });
            },
            // my_processed=我审批的
            loadFlowsProcessedByMe: function () {
                this.isLoading = true;
                processedByMe(this.userUuid, this.keyword, this.position).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.processedList = res.data.data.flows;
                    }
                    this.isLoading = false;
                });
            },
            // copy_to_me=抄送我的
            loadFlowsCopyByMe: function () {
                this.isLoading = true;
                copyByMe(this.userUuid, this.keyword, this.position).then(res => {
                    if (Util.isAjaxResOk(res)) {
                        this.copyList = res.data.data.flows;
                    }
                    this.isLoading = false;
                });
            },




            startFlow: function (flowId) {
                const url = this.url.flowOpen + '?flow=' + flowId + '&uuid=' + this.userUuid;
                window.open(url, '_blank');
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