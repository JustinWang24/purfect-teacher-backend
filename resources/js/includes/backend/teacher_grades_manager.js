import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";

if (document.getElementById('teacher-assistant-grades-manager-app')) {
  new Vue({
    el: '#teacher-assistant-grades-manager-app',
    data(){
      return {
        schoolId: null,
        gradeList: [],
        grade_id: null
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
      setId: function (id) {
        console.log(id)
        this.grade_id = id;
      },
      upload: function (file) {
        let params = new FormData();
        console.log(this.grade_id)
        params.append("grade_id", this.grade_id);  //图片
        params.append("file", file)
        const url = '/api/Oa/upload-grade-resources';
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            this.getGradeList()
            this.$message({
              message: '上传成功',
              type: 'success'
            });
          }
        }).catch((err) => {
              this.$message({
                message: '上传失败',
                type: 'warning'
              });
        });
        return false
      },
      remove (id) {
        const url = '/api/Oa/del-grade-resources'
        let params = {image_id: id};
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            this.getGradeList()
            this.$message({
              message: '删除成功',
              type: 'success'
            });
          }
        }).catch((err) => {
              this.$message({
                message: '删除失败',
                type: 'warning'
              });
        });
      }
    }
  });
}
