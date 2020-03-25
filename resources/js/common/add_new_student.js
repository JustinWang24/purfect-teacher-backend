import {Util} from "./utils";
import {Constants} from "./constants";


if (document.getElementById('school-add-student-app')) {
  new Vue({
    el: "#school-add-student-app",
    data : {
      major: "请选择",
      grade: "请选择",
      arr: [],
    },
    methods: {
      getMajors() {
          const url = Util.buildUrl(Constants.API.LOAD_MAJORS_BY_SCHOOL);
          axios.post(url, {
            id: 1
          }).then((res) => {
              if (Util.isAjaxResOk(res)) {
                 this.arr = res.data.data.majors
              }
          }).catch((err) => {
              console.log(err)
          });
      }
    }

  })
}
