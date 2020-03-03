import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('teacher-assistant-evaluation-app')) {
    new Vue({
        el: '#teacher-assistant-evaluation-app',
        data(){
            return {
                schoolId: null,
                filterOptions: [],
                filterValue: '',
                data: [],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                },
                tableData: [],
                ifShow: false,
                ifShowNote: false,
                nodeData: [],
                stuName: ''
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.getTimeScreen();
            console.log('评分');
        },
        methods: {
            searchList: function () {
                this.getTreeData();
                this.ifShow = false;
                this.ifShowNote = false;
            },
            showDetail: function (data) {
                this.ifShow = true;
                this.ifShowNote = false;
                this.getDetailData(data);
            },
            showNote: function (stuData) {
                this.ifShowNote = true;
                this.stuName = stuData.name;
                this.getNodeData(stuData);
            },
            getTreeData: function () {
                const url = Util.buildUrl(Constants.API.TEACHER_WEB.SIGN_IN_COURSES);
                let params = this.filterValue ? JSON.parse(this.filterValue) : '';
                delete params.name;
                if (!params) {
                    this.$message({
                        message: '请选择学期',
                        type: 'warning'
                    });
                    return false
                }
                ;
                axios.post(url, params).then((res) => {
                    if (Util.isAjaxResOk(res)) {
                        let data = res.data.data;
                        if (data.length > 0) {
                            this.data = [];
                            this.$message({
                                message: '查询成功',
                                type: 'success'
                            });
                            data.forEach((item, index) => {
                                let level1Item = {};
                                level1Item.id = item.course_id;
                                level1Item.label = item.name;
                                level1Item.children = [];
                                item.grade.forEach(function (item2, index2) {
                                    let level2Item = {};
                                    level2Item.id = item2.grade_id;
                                    level2Item.label = item2.name;
                                    level1Item.children.push(level2Item)
                                });
                                this.data.push(level1Item)
                            })
                        } else {
                            this.data = [];
                            this.$message({
                                message: '暂无数据',
                                type: 'warning'
                            });
                        }
                    }
                }).catch((err) => {
                    this.$message.error('接口调用失败');
                });
            },
            getTimeScreen: function () {
                const url = Util.buildUrl(Constants.API.TEACHER_WEB.TIME_SCREEN);
                axios.get(url).then((res) => {
                    if (Util.isAjaxResOk(res)) {
                        let data = res.data.data;
                        this.filterOptions = [];
                        data.forEach((item, index) => {
                            let options = {};
                            options.value = JSON.stringify(item);
                            options.label = item.name;
                            this.filterOptions.push(options)
                        });
                    }
                }).catch((err) => {

                });
            },
            getDetailData: function (data) {
                const url = Util.buildUrl(Constants.API.TEACHER_WEB.SIGN_IN_STUDENTLSIT);
                let params = this.filterValue ? JSON.parse(this.filterValue) : '';
                params.course_id = data.id;
                params.grade_id = data.data.id;
                axios.post(url, params).then((res) => {
                    if (Util.isAjaxResOk(res)) {
                        let data = res.data.data;
                        this.tableData = data.students;
                    }
                }).catch((err) => {
                    this.tableData = []
                });
            },
            getNodeData: function (data) {
                const url = Util.buildUrl(Constants.API.TEACHER_WEB.REMARK_LIST);
                let params = this.filterValue ? JSON.parse(this.filterValue) : {};
                params.user_id = data.user_id;
                axios.post(url, params).then((res) => {
                    if (Util.isAjaxResOk(res)) {
                        let data = res.data.data;
                        this.nodeData = data.list;
                    }
                }).catch((err) => {
                    this.nodeData = [];
                });
            }
        }
    });
}
