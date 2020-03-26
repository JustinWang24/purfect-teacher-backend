import {Util} from "./utils";
import {Constants} from "./constants";


if (document.getElementById('school-add-student-app')) {
  new Vue({
    el: "#school-add-student-app",
    data : {
      majors: [],
      grades: [],
      gradeId: '',
      majorId: '',
      year: '',
    },
    methods: {
      getMajors() {
          const dom = document.getElementById('app-init-data-holder');
          const schoolId = dom.dataset.school
          const url = Util.buildUrl(Constants.API.LOAD_MAJORS_BY_SCHOOL);
          axios.post(url, {
            id: schoolId
          }).then((res) => {
              if (Util.isAjaxResOk(res)) {
                 this.majors = res.data.data.majors
              }
          }).catch((err) => {
              console.log(err)
          });
      },
      getGrades() {
         const url = Util.buildUrl(Constants.API.LOAD_GRADES_BY_MAJOR);
          axios.post(url, {
            year: this.year, id:this.majorId
          }).then((res) => {
              if (Util.isAjaxResOk(res)) {
                 this.grades = res.data.data.grades
              }
          }).catch((err) => {
              console.log(err)
          });
      }
    }

  })
}
