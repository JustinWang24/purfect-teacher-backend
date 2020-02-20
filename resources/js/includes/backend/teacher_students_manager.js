import {Util} from "../../common/utils";

if (document.getElementById('teacher-assistant-students-manager-app')) {
    new Vue({
        el: '#teacher-assistant-students-manager-app',
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
                classData: [
                    {
                        class: '计算机一班',
                    }, {
                        class: '计算机一班',
                    }, {
                        class: '计算机一班',
                    }, {
                        class: '计算机一班',
                    }
                ],
                stuData: [
                    {
                        name: '王小虎',
                    }, {
                        name: '王小虎',
                    }, {
                        name: '王小虎',
                    }, {
                        name: '王小虎',
                    }
                ],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                },
                detailData: [
                    {
                        label: '姓名',
                        detail: '张三',
                        key: 'name'
                    }, {
                        label: '身份证号',
                        detail: 'xxx',
                        key: 'IDNum'
                    }, {
                        label: '性别',
                        detail: '男',
                        key: 'sex'
                    }, {
                        label: '出生日期',
                        detail: '1906',
                        key: 'barth'
                    }, {
                        label: '民族',
                        detail: '汉族',
                        key: 'name'
                    }, {
                        label: '政治面貌',
                        detail: '党员',
                        key: 'name'
                    }, {
                        label: '生源地',
                        detail: '广东',
                        key: 'name'
                    }, {
                        label: '籍贯',
                        detail: '广东',
                        key: 'name'
                    }, {
                        label: '联系电话',
                        detail: '123',
                        key: 'name'
                    }, {
                        label: 'QQ号',
                        detail: '33',
                        key: 'name'
                    }, {
                        label: '微信号',
                        detail: '33',
                        key: 'name'
                    }, {
                        label: '家长姓名',
                        detail: '老王',
                        key: 'name'
                    }, {
                        label: '家长电话',
                        detail: '123',
                        key: 'name'
                    }, {
                        label: '所在城市',
                        detail: '广州',
                        key: 'name'
                    }, {
                        label: '详细地址',
                        detail: '广州 ',
                        key: 'name'
                    }, {
                        label: '邮箱',
                        detail: 'abc@abc',
                        key: 'name'
                    }, {
                        label: '学制',
                        detail: '4',
                        key: 'name'
                    }, {
                        label: '学历',
                        detail: '本科',
                        key: 'name'
                    }, {
                        label: '学院',
                        detail: '国际贸易学院',
                        key: 'name'
                    }, {
                        label: '年级',
                        detail: '19级',
                        key: 'name'
                    }, {
                        label: '专业',
                        detail: '国际金融',
                        key: 'name'
                    }, {
                        label: '职务',
                        detail: '班长',
                        key: 'name'
                    }
                ],
                detailForm: {
                    name: '',
                    tel: '',
                    qq: '',
                    vx: '',
                    parentTel: '',
                    city: '',
                    address: '',
                    email: ''
                },
                ifShowStu: false,
                ifShowDetail: false,
                dialogVisible: false,
                teacherName: '评分教师:'
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            console.log('班级评分');
        },
        methods: {
            showStu: function (stuData) {
                this.ifShowStu = true;
                this.ifShowDetail = false;
                console.log(stuData)
                // this.stuName = stuData.stuName;
            },
            showDetail: function (data) {
                this.ifShowDetail = true;
                console.log(data)
            },
            editStu: function () {

            },
            onSubmit: function () {
                console.log('提交')
            }
        }
    });
}
