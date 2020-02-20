import {Util} from "../../common/utils";

if (document.getElementById('teacher-assistant-grades-evaluations-app')) {
    new Vue({
        el: '#teacher-assistant-grades-evaluations-app',
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
                        class: '计算机一班',
                        subject: 1,
                        evaluation: 1
                    }, {
                        class: '计算机一班',
                        subject: 1,
                        evaluation: 0
                    }, {
                        class: '计算机一班',
                        subject: 1,
                        evaluation: 0
                    }, {
                        class: '计算机一班',
                        subject: 1,
                        evaluation: 1
                    }
                ],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                },
                tableData: [
                    {
                        stuName: '王小虎',
                        stuMark: '8',
                        showNode: true
                    }, {
                        stuName: '张小虎',
                        stuMark: '9',
                        showNode: false
                    }, {
                        stuName: '李小虎',
                        stuMark: '18',
                        showNode: true
                    }, {
                        stuName: '赵小虎',
                        stuMark: '28',
                        showNode: false
                    }, {
                        stuName: '朱小虎',
                        stuMark: '38',
                        showNode: true
                    }
                ],
                ifShow: false,
                ifShowNote: false,
                teacherName: '评分教师:'
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            console.log('班级评分');
        },
        methods: {
            showDetail: function (data) {
                this.ifShow = true;
                this.ifShowNote = false;
                console.log(data)
            },
            showNote: function (stuData) {
                this.ifShowNote = true;
                console.log(stuData)
                // this.stuName = stuData.stuName;
            }
        }
    });
}
