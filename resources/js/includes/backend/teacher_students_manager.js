import {Util} from "../../common/utils";
import {Constants} from "../../common/constants";
import qs from 'qs'

if (document.getElementById('teacher-assistant-students-manager-app')) {
  new Vue({
    el: '#teacher-assistant-students-manager-app',
    data(){
      return {
        schoolId: null,
        classData: [],
        stuData: [],
        stuDetail: {},
        defaultProps: {
          children: 'children',
          label: 'label'
        },
        detailData: {},
        detailDataList: [
          {
            label: '姓名',
            detail: '',
            key: 'name'
          }, {
            label: '身份证号',
            detail: '',
            key: 'id_number'
          }, {
            label: '性别',
            detail: '男',
            key: 'gender'
          }, {
            label: '出生日期',
            detail: '',
            key: 'birthday'
          }, {
            label: '民族',
            detail: '',
            key: 'nation_name'
          }, {
            label: '政治面貌',
            detail: '',
            key: 'political_name'
          }, {
            label: '生源地',
            detail: '',
            key: 'source_place'
          }, {
            label: '籍贯',
            detail: '',
            key: 'country'
          }, {
            label: '联系电话',
            detail: '',
            key: 'contact_number'
          }, {
            label: 'QQ号',
            detail: '',
            key: 'qq'
          }, {
            label: '微信号',
            detail: '',
            key: 'wx'
          }, {
            label: '家长姓名',
            detail: '',
            key: 'parent_name'
          }, {
            label: '家长电话',
            detail: '',
            key: 'parent_mobile'
          }, {
            label: '所在城市',
            detail: '',
            key: 'city'
          }, {
            label: '详细地址',
            detail: ' ',
            key: 'address_line'
          }, {
            label: '邮箱',
            detail: '',
            key: 'email'
          }, {
            label: '学制',
            detail: '',
            key: 'school_year'
          }, {
            label: '学历',
            detail: '',
            key: 'education'
          }, {
            label: '学院',
            detail: '',
            key: 'institute'
          }, {
            label: '年级',
            detail: '',
            key: 'year'
          }, {
            label: '专业',
            detail: '',
            key: 'major'
          }, {
            label: '职务',
            detail: '',
            key: 'monitor'
          }, {
            label: '职务',
            detail: '',
            key: 'group'
          }
        ],
        detailForm: {
          contact_number: '',
          qq: '',
          wx: '',
          parent_mobile: '',
          city: '',
          address_line: '',
          email: '',
          position: ''
        },
        ifShowStu: false,
        ifShowDetail: false,
        dialogVisible: false,
        student_id: '',
        detailPage: {},
        studentsParams: {}
      }
    },
    created(){
      const dom = document.getElementById('app-init-data-holder');
      this.schoolId = dom.dataset.school;
      this.getClassData();
      // console.log('班级评分');
    },
    methods: {
      showStu: function (data) {
        this.ifShowStu = true;
        this.ifShowDetail = false;
        this.studentsParams = data;
        this.getStuData(data);
        // this.stuName = stuData.stuName;
      },
      showDetail: function (data) {
        this.ifShowDetail = true;
        let params = {student_id: data.student_id};
        this.student_id = data.student_id;
        this.getStuDetail(params)
      },
      editStu: function () {

      },
      onSubmit: function () {
        // console.log(this.detailForm);
        // console.log('提交');
        let params = {};
        params.data = {};
        params.monitor = {};
        params.group = {};
        params.monitor['monitor_name'] = '';
        params.monitor['monitor_id'] = 0;
        params.monitor['grade_id'] = this.detailData.grade_id;
        params.group['group_name'] = '';
        params.group['group_id'] = 0;
        params.group['grade_id'] = this.detailData.grade_id;
        for (let item in this.detailForm) {
          if (item != 'position') {
            // 循环赋值数组内容
            params.data[item] = this.detailForm[item]
          } else {
            // 班长/团支书/无
            let type = this.detailForm[item];
            if (type) {
              // type为'monitor'或'group' 出现哪个把哪个赋值;
              params[type][type + '_id'] = this.detailData.student_id;
              params[type][type + '_name'] = this.detailData.name;
            }
          }
        }
        params.student_id = this.student_id
        this.updateStudents(params);
      },
      getClassData: function () {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.STUDENTS_GRADE_LIST);
        axios.post(url).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.classData = data;
          }
        }).catch((err) => {

        });
      },
      getStuData: function (params) {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.STUDENTS_LIST);
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            this.stuData = res.data.data;
            this.$set(this,'detailPage',res.data)
          }
        }).catch((err) => {

        });
      },
      getStuDetail: function (params) {
        const url = Util.buildUrl(Constants.API.TEACHER_WEB.STUDENTS_INFO);
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            let data = res.data.data;
            this.setStuDetail(data)
          }
        }).catch((err) => {

        });
      },
      setStuDetail: function (data) {
        this.detailDataList.forEach(function (item, index) {
          item.detail = data[item.key];
        })
      },
      updateStudents: function (params) {
        const url = '/api/Oa/update-student-info';
        axios.post(url, params).then((res) => {
          if (Util.isAjaxResOk(res)) {
            this.$message({
              message: '保存成功',
              type: 'success'
            });
            let params = {student_id: this.student_id};
            this.dialogVisible = false;
            this.getStuDetail(params)
          }
        }).catch((err) => {
          this.$message({
            message: '保存失败',
            type: 'success'
          });
        });
      },
      detailChange: function (current) {
        this.studentsParams.page = current;
        this.getStuData(this.studentsParams)
      }
    }
  });
}
