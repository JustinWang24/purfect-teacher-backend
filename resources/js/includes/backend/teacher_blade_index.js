import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('teacher-assistant-index-app')) {
    new Vue({
        el: '#teacher-assistant-index-app',
        data(){
            return {
                schoolId: null,
                input: '',
                bannerData: [],
                statusMap: {
                    0: '未通过',
                    1: '已通过',
                    2: '待审批',
                    3: '已通过',
                    5: '已撤回'
                },
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
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.getBunnerData();
            // console('助手');
        },
        methods: {
            handleClick: function (tab, event) {
                // console(tab)
                // console(event)
            },
            getBunnerData: function () {
                const url = Util.buildUrl(Constants.API.TEACHER_WEB.INDEX);
                // console(url)
                axios.post(url).then((res) => {
                    // console(res)
                    if (Util.isAjaxResOk(res)) {
                        this.bannerData = res.data.data;
                    }
                }).catch((err) => {

                });
            }
        }
    });
}
