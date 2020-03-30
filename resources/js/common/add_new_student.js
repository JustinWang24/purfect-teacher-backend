import {Util} from "./utils";
import {Constants} from "./constants";


if (document.getElementById('school-add-student-app')) {
     const dom = document.getElementById('app-init-data-holder');
     const schoolId = dom.dataset.school

    new Vue({
        el: "#school-add-student-app",
        data : {
          majors: [],
          grades: [],
          institutes: [],
          gradeId: '',
          majorId: '',
          year: '',
        },
        methods: {
          getMajors() {
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
          },

          getInstitutes() {
            const url = Util.buildUrl(Constants.API.LOAD_INSTITUTES_BY_SCHOOL);
              axios.post(url, {
                school_id: schoolId
              }).then((res) => {
                  if (Util.isAjaxResOk(res)) {
                      this.institutes = res.data.data
                  }
              }).catch((err) => {
                  console.log(err)
              });
          },
        }
  })
}
