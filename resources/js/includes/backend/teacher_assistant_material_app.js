  import {Util} from "../../common/utils";
  import {Constants} from "../../common/constants";

  if (document.getElementById('teacher-assistant-material-app')) {
    new Vue({
      el: '#teacher-assistant-material-app',
      data() {
        return {
          schoolId: null, // 学校id
          activeIndex: 1, // 是否显示
          myCourseList: [], // 我的课程数据
          myMaterialsList: [], // 教学资料数据
          myMaterialsListData: [], // 教学资料内容展示数据

          // 计划日志
          showEditor: false, // 是否显示富文本编辑器
          showMaterialForm: true, // 是否显示富文本编辑器
          //course:null,
          //teacher:null,
          course:{id:35},
          teacher:{id:171870},
          notes:{
            teacher_notes:''
          },
          logs:[], // 当前课程的教学日志
          configOptions:{},
          types:[],
          courseMaterialModel:{
            id:null,
            //teacher_id:null,
            //course_id:null,
            teacher_id:171870,
            course_id:35,
            type: null,
            index: null,
            description: null,
            url: null,
            media_id: 0
          },
          logModel:{
            id:null,
            title: '',
            content: '',
          },
          // 目前被加载的课件材料
          lecture: {
            title:'',
            summary:'',
          },
          // 通过文件管理器来选择文件的功能所需
          showFileManagerFlag: false,
          // 教学日志表单控制
          showLogEditor: false,
          selectedFile:null,
          // 课时选择器
          highlight: 1,
          myCourseVisible: false, // 我的课程
          courseIndexerVisible: false, // 选择课件
          // 是否在从服务器加载数据中
          loadingData: false,
          // 所有该教师教授的当前的课程的班级集合
          grades:[],
          currentGradeId: null, // 当前选中的班级

        }
      },
      created() {
        const dom = document.getElementById('app-init-data-holder');
        this.schoolId = dom.dataset.school;
        this.changeMeans(1);
        this.getMyCourseListInfo();
        this.getMyMaterialsListInfo();
        this.myMaterialsListDataInfo(0); // 教学资料默认展示数据
      },
      methods: {
        changeMeans: function (val) {
          // 显示教学计划和教学日志数据
          if (val == 3) {
            this.loadTeacherNoteOrLogInfo();
          }
          this.activeIndex = val;
        },
        // 我的课程
        getMyCourseListInfo: function () {
          let _that_ = this;
          const url = Util.buildUrl(Constants.API.MATERIAL.POST_COURSE_URL);
          axios.post(url).then((res) => {
            if (Util.isAjaxResOk(res)) {
              console.log(res.data.data);
              _that_.myCourseList = res.data.data;
            }
          });
        },
        // 教学资料
        getMyMaterialsListInfo: function () {
          let _that_ = this;
          const url = Util.buildUrl(Constants.API.MATERIAL.POST_MATERIALS_URL);
          axios.post(url).then((res) => {
            if (Util.isAjaxResOk(res)) {
              if (res.data.data.length > 0) {
                _that_.myMaterialsList = res.data.data;
              }
            }
          });
        },
        activeTable: function (tab) {
          console.log(tab);
          this.myMaterialsListDataInfo(tab.index);
        },
        myMaterialsListDataInfo: function (index) {
          this.myMaterialsListData = this.myMaterialsList[index].list;
        },

        //------------------------------添加计划日志------------------------------------------------------
        // 获取教学计划和教学日志
        loadTeacherNoteOrLogInfo: function(){
          axios.post(
            '/teacher/course/materials/load-teacher-note',
            {teacher: this.teacher.id, course_id: this.course.id}
          ).then(res => {
            if(Util.isAjaxResOk(res)){
              this.notes = res.data.data.note;
              this.logs = res.data.data.logs;
            }
          });
        },
        // 显示编辑内容
        showNotesEditor: function(){
          this.showEditor = !this.showEditor;
        },
        // 保存教学计划
        saveNotes: function(){
          axios.post(
            '/teacher/course/materials/save-teacher-note',
            {notes: this.notes}
          ).then(res => {
            if(Util.isAjaxResOk(res)){
              this.showEditor = false;
              this.$message({
                type:'success',
                message:'课程简介保存成功'
              })
            }
          });
        },
        // 显示教学日志表单
        showLogEditorHandler: function(log){
          if(Util.isEmpty(log)){
            // 新增
            this.logModel.id = null;
            this.logModel.title = '';
            this.logModel.content = '';
          }else{
            this.logModel.id = log.id;
            this.logModel.title = log.title;
            this.logModel.content = log.content;
          }
          this.showLogEditor = true;
        },
        // 保存教学日志
        saveLog: function(){
         let _that_ = this;
          axios.post(
            '/teacher/course/materials/save-log',
            {log: this.logModel, teacher: this.teacher.id, course_id: this.course.id}
          ).then(res => {
            if(Util.isAjaxResOk(res)){
              this.showLogEditor = false;
              this.$message({
                type:'success',
                message:'教学日志保存成功'
              });
              if(Util.isEmpty(this.logModel.id)){
                // 新增数据
                _that_.logs.unshift({
                  id: res.data.data.id,
                  title: _that_.logModel.title,
                  content: _that_.logModel.content
                })
              }else{
                // 修改数据
                const idx = Util.GetItemIndexById(_that_.logModel.id, _that_.logs);
                _that_.logs[idx].title = _that_.logModel.title;
                _that_.logs[idx].content = _that_.logModel.content;
                console.log("---------编辑教学日志-----------");
                console.log(_that_.logs[idx]);
              }
            }
          });
        },
        // 删除教学日志
        deleteLog: function(log){
          console.log("---------删除教学日志-----------");
          console.log(log);
          this.logs.splice(Util.GetItemIndexById(log.id, this.logs),1);
          // TODO....未调用接口
        },




        editMaterial: function (id) {
          loadMaterial(id).then(res => {
            if(Util.isAjaxResOk(res)){
              this.courseMaterialModel = res.data.data.material;
            }
            else{
              this.$message.error('无法加载课件');
            }
          })
        },
        deleteMaterial: function(id){
          this.$confirm('此操作将永久删除该课件, 是否继续?', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            deleteMaterial(id).then(res => {
              if(Util.isAjaxResOk(res)){
                this.$message({
                  type:'success',
                  message:'删除成功'
                });
                window.location.reload();
              }
              else {
                this.$message.error('删除操作失败');
              }
            })
          }).catch(() => {
            this.$message({
              type: 'info',
              message: '已取消删除'
            });
          });
        },
        // 当云盘中的文件被选择
        pickFileHandler: function(payload){
          this.selectedFile = payload.file;
          this.showFileManagerFlag = false;
        }
      }
    });
  }
