import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('teacher-assistant-grades-evaluations-app')) {
  new Vue({
    el: '#teacher-assistant-grades-evaluations-app',
    data(){
      return {
        schoolId: null,
        date: '',
        filterOptions: [],
        filterValue: '',
        data: [],
        defaultProps: {
          children: 'children',
          label: 'label'
        },
        tableData: [],
        ifShow: false,
        detailPage: {},
        detailParams: {},
        teacherName: ''
      }
    },
    created(){
      const dom = document.getElementById('app-init-data-holder');
      this.schoolId = dom.dataset.school;
      this.getGradeList();
      console.log('班级评分');
    },
    methods: {
      searchList: function () {
        let params = this.filterValue ? JSON.parse(this.filterValue) : {};
        if (this.date) {
          params.date = this.date.getFullYear() + '-' + (this.date.getMonth() + 1) + '-' + this.date.getDate();
        }
        this.getGradeTable(params);
      },
      showDetail: function (data) {
        this.ifShow = true;

        this.detailParams = data;
        this.getGradeDetail(data)
      },
      getGradeList: function () {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.GRADE_LIST);
        axios.get(url).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.filterOptions = [];
            data.forEach((item, index) => {
              let options = {};
              options.value = JSON.stringify(item);
              options.label = item.grade_name;
              this.filterOptions.push(options)
            });
          }
        }).catch((err) => {

        });
      },
      getGradeTable: function (params) {
        console.log(params)
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.GRADE_TODAY_GRADE);
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.data = data.list;
          }
        }).catch((err) => {

        });
      },
      getGradeDetail: function (params) {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.GRADE_DETAIL);
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.detailPage = data;
            this.tableData = data.data;
            this.teacherName = '评分教师:' + data.teacher;
          }
        }).catch((err) => {

        });
      },
      detailChange: function (current) {
        this.detailParams.page = current;
        this.getGradeDetail( this.detailParams)
      }
    }
  });
}
