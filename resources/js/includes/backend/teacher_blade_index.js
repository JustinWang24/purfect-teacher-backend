import {Util} from "../../common/utils";

if (document.getElementById('teacher-assistant-index-app')) {
    new Vue({
        el: '#teacher-assistant-index-app',
        data(){
            return {
                schoolId: null,
                input:'',
                statusMap: {
                    0:'未通过',
                    1:'已通过',
                    2:'待审批',
                    3:'已通过',
                    5:'已撤回'
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
            console.log('助手');
        },
        methods: {
            handleClick: function (tab, event) {
                console.log(tab)
                console.log(event)
            }
        }
    });
}
