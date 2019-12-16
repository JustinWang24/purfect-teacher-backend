/**
 * 教材管理
 */
import {getMajors} from "../../common/timetables";
import {deleteTextbook, loadTextbooksPaginate} from "../../common/textbook";
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";
import {loadBuildings} from "../../common/facility";

if(document.getElementById('textbook-manager-app')){
    new Vue({
        el:'#textbook-manager-app',
        data(){
            return {
                userUuid: null,
                schoolId: null,
                books:[],
                total:0,
                pageNumber:0,
                pageSize:2,
                showTextbookFormFlag: false,
                textbookModel:{
                    type:1,
                    status:1,
                    medias:[],
                    courses:[],
                },
                // 自动补全搜索
                queryTextbook: '',
                queryType:'0',
                // 自动补全搜索完成
                showFileManagerFlag: false,
                showConnectedCoursesFlag: false,
                bookName: '',
                courses:[],
                isLoading: false,
                // 导出
                exportModel: {
                    type: '',
                    value: ''
                },
                majors:[],
                grades:[],
                campuses:[],
                showExportGradeFlag: false,
                showExportMajorFlag: false,
                showExportCampusFlag: false,
                // 导出功能完毕
            };
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.userUuid = dom.dataset.user;
            this.schoolId = dom.dataset.school;
            this.pageSize = parseInt(dom.dataset.size);
            axios.post(
                '/api/school/load-courses',
                {school: this.schoolId}
            ).then(res=>{
                if(Util.isAjaxResOk(res)){
                    this.courses = res.data.data.courses;
                }
            });

            this.loadTextbooks();
            this.resetForm();
        },
        methods: {
            resetForm: function(){
                this.textbookModel.id = null;
                this.textbookModel.name = '';
                this.textbookModel.edition = '';
                this.textbookModel.author = '';
                this.textbookModel.press = '';
                this.textbookModel.purchase_price = 0;
                this.textbookModel.price = 0;
                this.textbookModel.introduce = '';
                this.textbookModel.school_id = this.schoolId;
                this.textbookModel.type = 1;
                this.textbookModel.status = 1;
                this.textbookModel.medias = [];// 教材关联的图片
                this.textbookModel.courses = [];// 教材关联的课程
            },
            // 当文件从云盘管理器被选择会后的处理
            pickFileHandler: function(payload){
                this.textbookModel.medias.push(payload.file);
                this.showFileManagerFlag = false;
            },
            queryTextbooksAsync: function(queryString, cb){
                const _queryString = queryString.trim();
                if(Util.isEmpty(_queryString)){
                    // 如果视图搜索空字符串, 那么不执行远程调用, 而是直接回调一个空数组
                    cb([]);
                }
                else{
                    axios.post(
                        Constants.API.TEXTBOOK.SEARCH_TEXTBOOKS,
                        {query: _queryString, school: this.schoolId, scope: this.queryType}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            cb(res.data.data.books);
                        }
                    })
                }
            },
            handleReturnedTextbookSelect: function(item){
                this.textbookModel = item;
                this.showTextbookFormFlag = true;
            },
            // 导出功能
            exportBooksSheet: function(){
                this.showExportMajorFlag = false;
                if(this.exportModel.type === 'major'){
                    const u1 = Constants.API.TEXTBOOK.EXPORT_TEXTBOOKS_BY_MAJOR + '?major_id=' + this.exportModel.value;
                    window.open(u1, '_blank');
                }
                if(this.exportModel.type === 'campus'){
                    const u1 = Constants.API.TEXTBOOK.EXPORT_TEXTBOOKS_BY_CAMPUS + '?campus_id=' + this.exportModel.value;
                    window.open(u1, '_blank');
                }
            },
            exportByGrade: function(){
                if(this.grades.length === 0){
                    // 加载班级
                    this.isLoading = true;

                }
                this.showExportGradeFlag = true;
            },
            exportByMajor: function(){
                if(this.majors.length === 0){
                    // 加载班级
                    this.isLoading = true;
                    getMajors(this.schoolId, 0).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.majors = res.data.data.majors;
                        }
                        this.isLoading = false;
                    })
                }
                this.exportModel.type = 'major';
                this.showExportMajorFlag = true;
            },
            exportByCampus: function(){
                if(this.campuses.length === 0){
                    this.isLoading = true;
                    loadBuildings(this.schoolId).then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.campuses = res.data.data.campuses;
                        }
                        this.isLoading = false;
                    })
                }
                this.exportModel.type = 'campus';
                this.showExportCampusFlag = true;
            },
            // 导出功能结束
            getCourseNameText: function(courseId){
                const c = Util.GetItemById(courseId, this.courses);
                return c.name;
            },
            updateTextbookRelatedCourses: function(){
                axios.post(
                    '/teacher/textbook/update-related-courses',
                    {textbook: this.textbookModel.id, courses: this.textbookModel.courses}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.showConnectedCoursesFlag = false;
                        this.$message({
                            message: '保存成功: ' + this.textbookModel.name + '的关联课程已经更新了',
                            type: 'success'
                        });
                        // 一旦更新成功, 则刷新当前的列表
                        this.isLoading = true;
                    }
                })
            },
            // 关联课程
            connectCoursesAction: function(payload){
                // 加载该教材关联的课程
                this.textbookModel = payload.book;
                // 显示对话框
                this.showConnectedCoursesFlag = true;
            },
            addNewTextbook: function(){
                this.showTextbookFormFlag = true;
                this.resetForm();
            },
            // 编辑课本
            editBookAction: function(payload){
                this.textbookModel = payload.book;
                this.showTextbookFormFlag = true;
            },
            // 保存教材数据
            saveTextbook: function(){
                axios.post(
                    '/teacher/textbook/save',
                    {textbook: this.textbookModel}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.showTextbookFormFlag = false;
                        if(Util.isEmpty(this.textbookModel.id)){
                            // 新增教材的操作
                            this.books.unshift(res.data.data.textbook);
                        }
                        else{
                            // 更新操作
                            const idx = Util.GetItemIndexById(res.data.data.textbook.id, this.books);
                            if(idx > -1){
                                this.books[idx] = res.data.data.textbook;
                            }
                        }
                        this.resetForm();
                        this.$message({
                            message: '教材数据保存成功: ' + res.data.data.textbook.name,
                            type: 'success'
                        });
                    }else{
                        this.$notify.error({
                            title: '错误',
                            message: res.data.message,
                            position: 'bottom-right'
                        });
                    }
                })
            },
            // 删除教材数据
            deleteBookAction: function(payload){
                deleteTextbook(payload.book.id)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            const idx = Util.GetItemIndexById(payload.book.id, this.books);
                            if(idx > -1){
                                this.books.splice(idx, 1);
                                this.$message({
                                    message: '成功的删除了教材: ' + payload.book.name,
                                    type: 'success'
                                });
                            }
                        }
                        else{
                            this.$notify.error({
                                title: '系统错误',
                                message: '删除操作失败, 请稍候再试 ...',
                                position: 'bottom-right'
                            });
                        }
                    });
            },
            cancel: function(){
                this.showTextbookFormFlag = false;
            },
            selectedFileDeleted: function(payload){
                const idx = Util.GetItemIndexByUuid(payload.file.uuid, this.textbookModel.medias);
                this.textbookModel.medias.splice(idx, 1);
                this.$message({
                    message: '取消了文件: ' + payload.file.file_name,
                    type: 'success'
                });
            },
            loadTextbooks: function(){
                this.isLoading = true;
                loadTextbooksPaginate(
                    this.schoolId, this.userUuid, this.pageNumber, this.pageSize
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.books = res.data.data.books;
                        this.total = res.data.data.total;
                        this.pageNumber = res.data.data.p;
                        this.pageSize = res.data.data.s;
                    }
                    this.isLoading = false;
                })
            },
            // Pagination 的页码点击响应事件
            goToPage: function(val){
                this.pageNumber = val -1;
                this.loadTextbooks();
            }
        }
    });
}