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
      console.log('班级管理');
      this.getGradeList()
    },
    methods: {
      getGradeList: function () {
        const url = '/api/Oa/grade-list';
        axios.post(url).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            console.log(data)
            this.gradeList = data;
          }
        }).catch((err) => {

        });
      },
    }
  });
}
