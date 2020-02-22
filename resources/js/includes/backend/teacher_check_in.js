import {Util} from "../../common/utils";

if (document.getElementById('teacher-assistant-check-in-app')) {
    new Vue({
        el: '#teacher-assistant-check-in-app',
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
                        stuName: '王小虎',
                        stuNor: 40,
                        stuHoli: 4,
                        stuOff: 4
                    }, {
                        stuName: '张小虎',
                        stuNor: 20,
                        stuHoli: 4,
                        stuOff: 4
                    }, {
                        stuName: '赵小虎',
                        stuNor: 20,
                        stuHoli: 4,
                        stuOff: 4
                    }, {
                        stuName: '李小虎',
                        stuNor: 20,
                        stuHoli: 4,
                        stuOff: 4
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
