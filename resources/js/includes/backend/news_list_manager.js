/**
 * 动态新闻的管理
 */
import {
    deleteNews,
    deleteSection,
    loadNews,
    moveDownSection,
    moveUpSection, publishNews,
    saveNews,
    saveSections
} from "../../common/news";
import {Constants} from "../../common/constants";
import {Util} from "../../common/utils";

if(document.getElementById('school-news-list-app')){
    new Vue({
        el:'#school-news-list-app',
        data(){
            return {
                schoolId: null,
                newsForm:{
                    title: '',
                    id:'',
                    type: 1
                },
                selectedImgUrl: null,
                mediaForm:{
                    content: '',
                    media_id: null,
                    id:'',
                    position:1,
                },
                sections:[],
                newsFormFlag:false,
                sectionsFormFlag:false,
                textContentWrapFlag: false,
                mediaContentWrapFlag: false,
                showFileManagerFlag: false,
                formLabelWidth: '100px',
                loading: false,
                // 当前的新闻列表
                news:[],
                totalNews: 0,
                dndOptions:{},
            }
        },
        created(){
            const dom = document.getElementById('app-init-data-holder');
            this.schoolId = dom.dataset.school;
            this.newsForm.type = parseInt(dom.dataset.type);// 文章类型
            const injectedData = JSON.parse(dom.dataset.news);// 文章类型

            // 加载文章列表
            this.news = injectedData.data;
            this.totalNews = injectedData.total;
        },
        methods: {
            // 新闻的 sections 的编辑
            moveUp: function(section){
                moveUpSection(section.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const idx = Util.GetItemIndexById(section.id, this.sections);
                        this.sections = Util.swapArray(this.sections, idx -1, idx);
                    }
                    else{
                        this.$message.error('系统繁忙, 请稍候再试');
                    }
                });
            },
            moveDown: function(section){
                moveDownSection(section.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const idx = Util.GetItemIndexById(section.id, this.sections);
                        this.sections = Util.swapArray(this.sections, idx, idx + 1);
                    }
                    else{
                        this.$message.error('系统繁忙, 请稍候再试');
                    }
                });
            },
            editSection: function(section){
                this.newsFormFlag = false;
                this.sectionsFormFlag = true;
                // 判断此段落是什么类型
                const type = Util.checkArticleSectionType(section);
                const that = this;
                const keys = Object.keys(section);
                keys.forEach(function(key){
                    that.mediaForm[key] = section[key];
                });

                switch (type){
                    case Constants.SECTION_TYPE.TEXT:
                        // 文字类型
                        this.textContentWrapFlag = true;
                        this.mediaContentWrapFlag = false;
                        break;
                    case Constants.SECTION_TYPE.IMAGE:
                        break;
                    default:
                        break;
                }
            },
            deleteSection: function(section){
                this.$confirm('此操作将永久删除该段落, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    deleteSection(section.id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            const idx = Util.GetItemIndexById(section.id, this.sections);
                            this.sections.splice(idx, 1);
                            this.$message({
                                type:'success',
                                message:'文章段落删除成功'
                            });
                        }
                        else{
                            this.$message.error('系统繁忙, 请稍候再试');
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            // 新闻的 sections 的编辑完毕
            addNew: function(){
                this.newsFormFlag = true;
                this.newsForm.title = '';
                this.newsForm.id = '';
                this.sections = [];
            },
            publish: function(){
                publishNews(this.schoolId, this.newsForm.id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.$message({
                            message: '发布成功',
                            type: 'success'
                        });
                        const idx = Util.GetItemIndexById(this.newsForm.id, this.news);
                        if(idx > -1){
                            this.news[idx].publish = true;
                        }
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })
            },
            saveNews: function(){
                // Todo 保存新闻
                saveNews(this.schoolId, this.newsForm).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.newsFormFlag = false;
                        this.sectionsFormFlag = true;
                        if(!Util.isEmpty(res.data.data.news)){
                            this.newsForm.id = res.data.data.news.id;
                            this.news.push(this.newsForm);
                        }
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })
            },
            cancelSaveNews: function(){
                this.newsFormFlag = false;
            },
            addNewTextSection: function(){
                this.textContentWrapFlag = true;
            },
            addNewMediaSection: function(){
                this.mediaContentWrapFlag = true;
            },
            loadNews: function(id){
                this.loading = true;
                loadNews(id).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.newsForm = res.data.data.news;
                        this.sections = res.data.data.news.sections;
                        this.newsFormFlag = true;
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                    this.loading = false;
                })
            },
            deleteNews: function(id){
                this.$confirm('此操作将永久删除该动态, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.loading = true;
                    deleteNews(this.schoolId, id).then(res => {
                        if(Util.isAjaxResOk(res)){
                            window.location.reload();
                        }
                        else{
                            this.$message.error('删除失败, 请稍候再试');
                            this.loading = false;
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            pushNewSection: function(){
                if(Util.isEmpty(this.mediaForm.content) && Util.isEmpty(this.mediaForm.media_id)){
                    this.$message.error('您没有输入内容, 无法保存');
                    return;
                }

                if(!Util.isEmpty(this.mediaForm.media_id)){
                    this.mediaForm.content = this.selectedImgUrl;
                }

                saveSections(this.newsForm.id, [this.mediaForm]).then(res => {
                    if(Util.isAjaxResOk(res)){
                        const that = this;
                        if(Util.isEmpty(res.data.data.id)){
                            // 更新
                            const idx = Util.GetItemIndexById(this.mediaForm.id, this.sections);
                            const keys = Object.keys(this.mediaForm);
                            keys.forEach(function(key){
                                that.sections[idx][key] = that.mediaForm[key];
                            });
                        }
                        else{
                            // 新增段落的处理
                            const media = {};
                            const keys = Object.keys(this.mediaForm);
                            keys.forEach(function(key){
                                media[key] = that.mediaForm[key];
                            });
                            media.id = res.data.data.id;
                            this.sections.push(media);
                        }

                        this.resetMediaForm();
                        this.$message({type:'success',message:'段落保存成功'});
                    }
                    else{
                        this.$message.error(res.data.data.message);
                    }
                })
            },
            // 关闭添加 section 的表单
            cancelNewSection: function(type){
                if(type ===1){
                    this.textContentWrapFlag = false;
                    this.mediaForm.content = '';
                }
                else if(type ===2){
                    this.mediaContentWrapFlag = false;
                    this.mediaForm.media_id = null;
                }
            },
            resetMediaForm: function(){
                this.mediaForm.id = '';
                this.mediaForm.content = '';
                this.mediaForm.media_id = null;
                this.mediaForm.position = '';
                this.selectedImgUrl = null;
            },
            pickFileHandler: function(payload){
                this.mediaForm.media_id = payload.file.id;
                this.selectedImgUrl = payload.file.url;
                this.showFileManagerFlag = false;
            },
        }
    })
}