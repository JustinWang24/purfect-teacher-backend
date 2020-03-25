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
      upload: function (file) {
        let params = {};
        params.grade_id = 10;
        console.log(file)
        // params.file = file.raw;
        // console.log()
        // const url = '/api/Oa/upload-grade-resources';
        // axios.post(url, params).then((res) => {
        //   if (Util.isAjaxResOk(res)) {
        //     console.log(res)
        //   }
        // }).catch((err) => {
        //
        // });
        this.getBase64(file.raw).then(res => {
          // params.file = this.base64ToBlob(res);
          console.log(params.file)
          console.log(params)
          const url = '/api/Oa/upload-grade-resources';
          axios({
            method: 'post',
            url: url,
            data: params,
          }).then((res) => {
            if (Util.isAjaxResOk(res)) {
              console.log(res)
            }
          }).catch((err) => {

          });
        })

      },
      getBase64(file) {
        return new Promise(function (resolve, reject) {
          let reader = new FileReader();
          let imgResult = "";
          reader.readAsDataURL(file);
          reader.onload = function () {
            imgResult = reader.result;
          };
          reader.onerror = function (error) {
            reject(error);
          };
          reader.onloadend = function () {
            resolve(imgResult);
          };
        });
      },
      base64ToBlob(code) {
        let parts = code.split(";base64,");
        let contentType = parts[0].split(":")[1];
        let raw = window.atob(parts[1]);
        let rawLength = raw.length;

        let uInt8Array = new Uint8Array(rawLength);

        for (let i = 0; i < rawLength; ++i) {
          uInt8Array[i] = raw.charCodeAt(i);
        }
        return new Blob([uInt8Array], {type: contentType});
      }
    }
  });
}
