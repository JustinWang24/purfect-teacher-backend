import {Util} from "../../common/utils";

if (document.getElementById('teacher-assistant-grades-check-in-app')) {
    new Vue({
        el: '#teacher-assistant-grades-check-in-app',
        data(){
            return {
                schoolId: null,
                filterOptions: [
                    {
                        value: '选项1',
                        label: '黄金糕'
                    }, {
                        value: '选项2',
                        label: '双皮奶'
                    }, {
                        value: '选项3',
                        label: '蚵仔煎'
                    }, {
                        value: '选项4',
                        label: '龙须面'
                    }, {
                        value: '选项5',
                        label: '北京烤鸭'
                    }
                ],
                filterValue: '',
                data: [
                    {
                        label: '计算机一班',
                        level: 1,
                        children: [{
                            level: 2,
                            label: '语文'
                        }, {
                            level: 2,
                            label: '语文'
                        }]
                    }, {
                        label: '计算机2班',
                        level: 1,
                        children: [{
                            level: 2,
                            label: '语文'
                        }]
                    }, {
                        label: '计算机3班',
                        level: 1,
                        children: [{
                            level: 2,
                            label: '语文'
                        }]
                    }
                ],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                },
                tableData: [
                    {
                        class: '第一节',
                        stuNor: 40,
                        stuHoli: 4,
                        stuOff: 4
                    }, {
                        class: '第二节',
                        stuNor: 20,
                        stuHoli: 4,
                        stuOff: 4
                    }, {
                        class: '第三节',
                        stuNor: 20,
                        stuHoli: 4,
                        stuOff: 4
                    }, {
                        class: '第四节',
                        stuNor: 20,
                        stuHoli: 4,
                        stuOff: 4
                    }
                ],
                studentsStatus: {
                    1: '已签',
                    2: '请假',
                    3: '旷课'
                },
                detailData: [
                    {
                        stuName: '王小虎',
                        checkin_date: '1994',
                        stuStatus: 1,
                    }, {
                        stuName: '王小虎',
                        checkin_date: '1994',
                        stuStatus: 2,
                    }, {
                        stuName: '王小虎',
                        checkin_date: '1994',
                        stuStatus: 3,
                    }
                ],
                ifShow: false
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            console.log('签到');
        },
        methods: {

            showDetail: function (data) {
                this.ifShow = true;
                console.log(data)
            }
        }
    });
}
