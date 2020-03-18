import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('teacher-assistant-grades-manager-app')) {
  new Vue({
    el: '#teacher-assistant-grades-manager-app',
    data(){
      return {
        schoolId: null,
        gradeList: []
      }
    },
    created(){
      const dom = document.getElementById('app-init-data-holder');
      this.schoolId = dom.dataset.school;
      // 载入时间选择
      console.log('1');
      this.getGradeList()
    },
    methods: {
      getGradeList: function () {
        const url = '/api/Oa/grade-resources';
        axios.post(url).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            // console.log(data)
            this.gradeList = data;
          }
        }).catch((err) => {

        });
      },
      upload: function (e,file) {
        console.log(e, file)
        let params  = {};
        params.grade_id = 10;
        params.file = file;
        const url = '/api/Oa/upload-grade-resources';
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            console.log(res)
          }
        }).catch((err) => {

        });
      }
    }
  });
}
