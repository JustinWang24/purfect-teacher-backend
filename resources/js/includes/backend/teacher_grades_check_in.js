import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('teacher-assistant-grades-check-in-app')) {
  new Vue({
    el: '#teacher-assistant-grades-check-in-app',
    data(){
      return {
        schoolId: null,
        date: '',
        gradeOptions: [],
        gradeValue: '',
        defaultProps: {
          children: 'children',
          label: 'label'
        },
        tableData: [],
        studentsStatus: {
          1: '已签',
          2: '请假',
          3: '旷课'
        },
        detailData: [],
        detailPage: {},
        attendance_id: '',
        ifShow: false
      }
    },
    created(){
      const dom = document.getElementById('app-init-data-holder');
      this.schoolId = dom.dataset.school;
      this.getGradeList();
      // console.log('签到');
    },
    methods: {
      searchList: function () {
        let params = this.gradeValue ? JSON.parse(this.gradeValue) : {};
        if (this.date) {
          params.date = params.date = this.date.getFullYear() + '-' + (this.date.getMonth() + 1) + '-' + this.date.getDate();
        }
        this.ifShow = false;
        this.getGradeSignin(params);
      },
      showDetail: function (data) {
        this.ifShow = true;
        var params = {};
        params.attendance_id = data.attendance_id;
        this.attendance_id = data.attendance_id;
        // console.log(this.attendance_id, data.attendance_id)
        this.getGradeDetail(params);
      },
      getGradeSignin: function (params) {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.GRADE_SINGIN);
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.tableData = data.list;
          }
        }).catch((err) => {

        });
      },
      getGradeList: function () {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.GRADE_LIST);
        axios.get(url).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.gradeOptions = [];
            data.forEach((item, index) => {
              let options = {};
              options.value = JSON.stringify(item);
              options.label = item.grade_name;
              this.gradeOptions.push(options)
            });
          }
        }).catch((err) => {
          this.gradeOptions = [];
        });
      },
      getGradeDetail: function (params) {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.GRADE_SINGIN_DETAIL);
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.detailPage = data;
            this.detailData = data.list;
          }
        }).catch((err) => {

        });
      },
      detailChange: function (current) {
        let params = {};
        params.attendance_id = this.attendance_id;
        // console.log(this.attendance_id)
        params.page = current;
        this.getGradeDetail(params)
      }
    }
  });
}
