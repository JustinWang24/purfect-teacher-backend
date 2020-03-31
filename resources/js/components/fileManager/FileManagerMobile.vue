<template>
    <div class="file-manager-wrapper">
        <div class="manager-body">
            <div class="files-list-wrapper">
                <div class="the-wrapper main-title">
                    <p class="section-title">
                        <i class="el-icon-folder-opened"></i>
                        文件列表&nbsp;
                        <i class="el-icon-loading" v-show="isLoading"></i>&nbsp;
                    </p>
                    <ul class="path">
                        <li class="path-item" >
                            <el-button style="margin: 5px;" v-on:click="createNewFolder" size="mini" class="btn-theme" icon="el-icon-folder-add"></el-button>
                        </li>
                        <li class="path-item" v-for="(item, idx) in path" :key="idx">
                            &nbsp;<el-button style="font-size: 12px;" v-on:click="loadCategory(item.uuid)" :class="idx>0?'btn-purple':''" type="text" :disabled="idx===0">{{ item.name }} / </el-button>
                        </li>
                    </ul>
                </div>
                <div class="files-wrap">
                    <div v-show="showNewCategoryFormFlag">
                        <new-category-form
                                v-on:category-name-confirmed="newCategoryNameConfirmed"
                                v-on:category-cancelled="newCategoryCancelled"
                        ></new-category-form>
                    </div>
                    <div  v-for="(cat, idx) in categories" :key="cat.uuid">
                        <category-item
                                v-on:item-clicked="handleCategoryItemClicked"
                                v-on:change-category="changeCategoryHandler"
                                v-on:category-removed="removedCategoryHandler"
                                :file="cat"
                                :has-new="cat.hasNew"
                                :highlight="idx === idxSelectedCategory"
                        ></category-item>
                    </div>
                    <div v-for="(file, idx) in returnedFiles" :key="idx">
                        <file-item-mobile
                                v-on:item-clicked="handleFileItemClicked"
                                v-on:file-removed="handleFileItemRemoved"
                                v-on:star-clicked="handleFileStarClicked"
                                v-on:file-moved="handleFileMoved"
                                :file="file"
                                :highlight="idx === idxReturnedFile"
                                :init-categories="initCategories"
                                :user-uuid="userUuid"
                        ></file-item-mobile>
                    </div>
                </div>
                <div class="space-summary">
                    <p>
                        总文件数: <span>{{ disk.total }}</span>&nbsp;占用空间: <span>{{ fileSize(disk.use_size) }}</span>&nbsp;全部空间: <span>{{ fileSize(disk.total_size) }}</span>
                    </p>
                </div>
            </div>
            <div class="file-preview-wrapper">
                <div class="the-wrapper">
                    <p class="section-title">
                        <i class="el-icon-upload"></i>&nbsp;文件上传到:
                        <span class="btn-purple" v-on:click="loadCategory(selectedCategory.uuid)">{{ selectedCategory.name }}</span>
                    </p>
                    <div class="upload-box">
                        <el-upload
                                class="upload-demo"
                                ref="uploadForm"
                                :data="uploadFormData"
                                :multiple="false"
                                :headers="headers"
                                :limit="1"
                                :action="fileUploadActionUrl"
                                :before-upload="onBeforeUpload"
                                :on-success="onFileUploaded"
                                :on-error="onFileUploadFailed"
                                :on-change="onSelectFileChange"
                                :on-remove="handleRemove"
                                :file-list="newFiles"
                                :auto-upload="false">

                                <el-button slot="trigger" size="mini" class="btn-theme" icon="el-icon-plus">
                                    文件
                                </el-button>
                                <el-button style="margin-left: 10px;" size="mini" type="success" @click="submitUpload" icon="el-icon-upload2">
                                    上传
                                </el-button>
                                <span slot="tip" class="el-upload__tip">{{ currentUploadFileSizeText }}</span>

                        </el-upload>
                        <el-form ref="form" :model="uploadFormData" label-width="80px">
                            <el-input style="margin-top: 10px;"  placeholder="选填: 文件描述关键字" v-model="uploadFormData.keywords"></el-input>
                            <el-input style="margin-top: 10px;" type="textarea" autosize v-model="uploadFormData.description" placeholder="选填: 文件说明"></el-input>
                        </el-form>
                    </div>

                    <div class="preview-box-title">
                        <p class="section-title" style="margin-top: 3px;">文件预览/详情</p>
                        <el-button
                                class="thebtn"
                                size="mini"
                                type="success"
                                v-if="pickFile" v-on:click="pickThisFile"
                        >使用文件</el-button>
                    </div>
                    <file-preview :file-dic="selectedFile"></file-preview>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import RecentFile from './elements/RecentFile';
    import FileItemMobile from './elements/FileItemMobile';
    import CategoryItem from './elements/CategoryItem';
    import NewCategoryForm from './elements/NewCategoryForm';
    import FilePreview from './elements/FilePreview';
    import {Util} from '../../common/utils';
    import {Constants} from '../../common/constants';
    import { createNewCategoryAction, recentFilesAction, networkDiskSizeAction, loadCategory, updateAsteriskAction, searchFileAction } from '../../common/file_manager';
    import ElementUI from 'element-ui';
    Vue.use(ElementUI);

    export default {
        name: "FileManagerMobile",
        props:['userUuid','pickFile','allowedFileTypes'],
        components:{
            RecentFile,FileItemMobile,CategoryItem,NewCategoryForm,FilePreview
        },
        data() {
            return {
                // 当前被选择的目录
                selectedCategory:{
                    uuid:'',
                    name:''
                },
                // 当前被选择的文件
                selectedFile: null,
                // 被加载的所有文件夹
                categories: [],
                // 保存的当前的路径
                path:[],
                // 搜索文件框的关键字
                query: '',
                searchedFiles:[],
                // 被搜到的文件的列表
                returnedFiles: [],
                recentFiles:[],
                disk:{
                    use_size: 0,
                    total_size: 0
                },
                fileUploadActionUrl: '',
                newFiles:[], // 文件上传
                // 当前高亮的几种类型值
                idxSelectedCategory: -1,
                idxReturnedFile: -1,
                idxRecentFile: -1,
                // 是否当前正在从服务器加载数据
                isLoading: false,
                showNewCategoryFormFlag: false,
                newCategoryNameIdx: 0,
                uploadFormData: {
                    category: null,
                    user: this.userUuid,
                    keywords: '',
                    description: '',
                },
                headers:{},
                currentUploadFileSize: '',
                // 初始化的目录
                initCategories: [],
            }
        },
        computed: {
            'currentUploadFileSizeText': function(){
                if(this.currentUploadFileSize === 0){
                    return '';
                }
                else{
                    return '本次上传文件大小: ' + Util.fileSize(this.currentUploadFileSize);
                }
            },
            'spaceLeft': function(){
                return Util.fileSize(this.disk.total_size - this.disk.use_size);
            }
        },
        created() {
            this.loadCategory();
            this._loadRecentFiles();
            this._getMyDisk();
            this.fileUploadActionUrl = Constants.API.FILE_MANAGER.FILE_UPLOAD;
            this.headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                'Authorization': 'Bearer ' + document.head.querySelector('meta[name="api-token"]').content,
                'Accept': 'application/json',
            };
        },
        methods: {
            pickThisFile: function(){
                this.$emit('pick-this-file',{file: this.selectedFile});
                this.$message({
                    message: '选择了文件: ' + this.selectedFile.file_name,
                    type: 'success'
                });
            },
            fileSize: function(size){
                return Util.fileSize(size);
            },
            handleReturnedFileSelect: function(item) {
                // todo 以后对不同文件的类型, 点击搜索结果后做最佳的处理.
                window.open(item.url, '_blank');
            },
            querySearchFilesAsync: function(queryString, cb){
                const _queryString = queryString.trim();
                if(Util.isEmpty(_queryString)){
                    // 如果视图搜索空字符串, 那么不执行远程调用, 而是直接回调一个空数组
                    cb([]);
                }
                else{
                    searchFileAction(this.userUuid, queryString)
                        .then(res => {
                            if(Util.isAjaxResOk(res)){
                                cb(res.data.data.files)
                            }
                        })
                }
            },
            // 文件上传相关
            onSelectFileChange: function(file, fileList){
                return true;
            },
            handleRemove: function(file, fileList){
                return true;
            },
            onFileUploadFailed: function(err, file, fileList){
                this.$message.error('不支持此种格式的文件上传到云盘或者文件尺寸太大');
                this.uploadFormData.keywords = '';
                this.uploadFormData.description = '';
                this.currentUploadFileSize = 0;
                fileList.pop();
            },
            onFileUploaded: function(response, file, fileList){
                this.uploadFormData.keywords = '';
                this.uploadFormData.description = '';
                this.currentUploadFileSize = 0;
                this.disk.total++;

                if(response.code === Constants.AJAX_SUCCESS){
                    if(this.idxSelectedCategory === -1){
                        this.returnedFiles.unshift(response.data.file);
                    }else{
                        this.categories[this.idxSelectedCategory].hasNew = true;
                    }
                    fileList.pop();
                    this.$message({
                        message: '文件上传成功!',
                        type: 'success'
                    });
                }else{
                    this.$message.error('错了哦: ' + response.message);
                }
            },
            submitUpload: function(){
                this.uploadFormData.category = this.selectedCategory.uuid;
                console.log( this.$refs.uploadForm)
                this.$refs.uploadForm.submit();
            },
            onBeforeUpload: function(file){
                return file.size < Constants.MAX_UPLOAD_FILE_SIZE;
            },
            // 文件上传相关结束
            // 创建新的文件夹
            createNewFolder: function(){
                this.showNewCategoryFormFlag = true;
            },
            newCategoryNameConfirmed: function(payload){
                // 确认创建新的文件夹
                createNewCategoryAction(
                    this.userUuid, this.selectedCategory.uuid, payload.name
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.showNewCategoryFormFlag = false;
                        this.loadCategory(this.selectedCategory.uuid);
                        this.$message({
                            type: 'success',
                            message: '文件夹: "' + payload.name + '"创建成功!'
                        });
                    }else{
                        this.$message.error('操作失败: ' + res.data.message);
                    }
                }).catch(e => {
                    this.$message.error('系统繁忙, 请稍候再试!');
                })
            },
            newCategoryCancelled: function(){
                this.showNewCategoryFormFlag = false;
            },
            // 获取目录详情的方法
            loadCategory: function(categoryId){
                this.isLoading = true;
                loadCategory(this.userUuid, categoryId)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.categories = res.data.data.category.children;
                            if(Util.isEmpty(categoryId)){
                                this.initCategories = this.categories;
                            }

                            this.returnedFiles = res.data.data.category.files;
                            this.selectedCategory.uuid = res.data.data.category.uuid;
                            this.selectedCategory.name = res.data.data.category.name;

                            this.path = [];
                            this.path.push(this.selectedCategory);
                            this.path.push(res.data.data.category.parent);
                            this.resetAllHighlightIndex();
                        }else{
                            this.$message.error('访问的云盘目录不存在');
                        }
                        this.isLoading = false;
                    });
            },
            changeCategoryHandler: function(payload){
                this.loadCategory(payload.file.uuid);
            },
            removedCategoryHandler: function(payload){
                const idx = Util.GetItemIndexByUuid(payload.file.uuid, this.categories);
                if(idx > -1){
                    this.categories.splice(idx, 1);
                }
            },
            handleFileItemRemoved: function(payload){
                if(payload.type === 'file'){
                    const idx = Util.GetItemIndexByUuid(payload.file.uuid, this.returnedFiles);
                    if(idx > -1){
                        this.returnedFiles.splice(idx, 1);
                    }
                }
                else if(payload.type === 'recent'){
                    const idx = Util.GetItemIndexByUuid(payload.file.uuid, this.recentFiles);
                    if(idx > -1){
                        this.recentFiles.splice(idx, 1);
                    }
                }
            },
            // 当文件的星标被点击后的处理函数
            handleFileStarClicked: function(payload){
                updateAsteriskAction(this.userUuid, payload.file.uuid)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            if(payload.clicked === 'recent'){
                                const idx = Util.GetItemIndexById(payload.file.id, this.recentFiles);
                                this.recentFiles[idx].asterisk = !this.recentFiles[idx].asterisk;
                            }else{
                                const idx = Util.GetItemIndexById(payload.file.id, this.returnedFiles);
                                this.returnedFiles[idx].asterisk = !this.returnedFiles[idx].asterisk;
                            }
                        }
                    })
            },
            // 当文件被点击后的处理函数
            handleFileItemClicked: function(payload){
                // 文件条目被点击, 是要进行 高亮/预览 的操作
                this.resetAllHighlightIndex();
                if(payload.clicked === 'file'){
                    this.idxReturnedFile = Util.GetItemIndexByUuid(payload.file.uuid, this.returnedFiles);
                    this.selectedFile = this.returnedFiles[this.idxReturnedFile];
                }
                else if(payload.clicked === 'recent'){
                    this.idxRecentFile = Util.GetItemIndexByUuid(payload.file.uuid, this.recentFiles);
                    this.selectedFile = this.recentFiles[this.idxRecentFile];
                }
            },
            // 当文件被移动, 如果成功, 则从当前的文件列表中抹去
            handleFileMoved: function(payload){
                if(payload.to.uuid !== this.selectedCategory.uuid){
                    const idx = Util.GetItemIndexByUuid(payload.file.uuid, this.returnedFiles);
                    this.returnedFiles.splice(idx, 1);
                }
            },
            handleCategoryItemClicked: function(payload){
                // 目录条目被点击, 是要进行打开的操作
                this.resetAllHighlightIndex();
                this.idxSelectedCategory = Util.GetItemIndexByUuid(payload.file.uuid, this.categories);
                this.selectedCategory.name = payload.file.name;
                this.selectedCategory.uuid = payload.file.uuid;
            },
            resetAllHighlightIndex: function(){
                this.idxSelectedCategory = -1;
                this.idxReturnedFile = -1;
                this.idxNewFile = -1;
                this.idxRecentFile = -1;
            },
            // 获取用户最近浏览的文件
            _loadRecentFiles: function(){
                recentFilesAction(this.userUuid)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.recentFiles = res.data.data.browse;
                        }
                    })
            },
            // 获取用户的网盘容量
            _getMyDisk: function(){
                networkDiskSizeAction(this.userUuid)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.disk = res.data.data.size;
                        }
                    })
            }
        }
    }
</script>

<style scoped lang="scss">
    $themeColor: rgb(98, 109,183);
    $themeGreyColor: rgb(239, 243,248);
    $colorGrey: #c9cacc;
    .empty-tip-txt{
        color: $colorGrey;
        font-size: 11px;
        padding-left: 10px;
    }
    .file-manager-wrapper{
        display: flex;
        flex-direction: column;
        height: 100%;
        margin-top:-31px;
        .manager-body{
            display: flex;
            flex-direction: column;
            height: 100%;
            margin-top: 14px;
            background-color: #eff3f8;
            .files-list-wrapper{
                display: block;
                background-color: $themeGreyColor;
                .the-wrapper{
                    padding-top: 5px;
                    padding-left: 5px;
                    padding-right: 5px;
                    .section-title{
                        font-size: 16px;
                        line-height: 30px;
                        font-weight: bold;
                        padding: 0;
                        margin:5px;
                    }
                    .upload-box{
                        margin-right: 10px;
                    }
                }
                .main-title{
                    display: flex;
                    justify-content: space-between;
                    padding-right: 12px;
                    .path{
                        display: flex;
                        flex-direction: row-reverse;
                        margin-top: -10px;
                        .path-item{
                            font-size: 15px;
                            margin: 10px 0;
                            list-style: none;
                        }
                    }
                }
                .recent-files-wrap{
                    display: flex;
                    padding-left: 41px;
                    .file-item{
                        margin: 10px;
                    }
                }
                .files-wrap{
                    display: flex;
                    flex-direction: column;
                    padding-left: 10px;
                    width: 96%;
                    overflow: scroll;
                    margin-top: -20px;
                }
                .space-summary{
                    padding-top: 10px;
                    p{
                        text-align: center;
                        font-size: 12px;
                        color: $themeColor;
                        span{
                            color: #333333;
                        }
                    }
                }
            }
            .file-preview-wrapper{
                display: block;
                background-color: $themeGreyColor;
                padding-top: 0;
                margin-top: 1px;
                .the-wrapper{
                    padding-right: 20px;
                    padding-left: 10px;
                    .section-title{
                        font-size: 16px;
                        line-height: 30px;
                        font-weight: bold;
                        margin-top: 10px;
                        margin-bottom: 10px;
                    }
                    .preview-box-title{
                        margin-top: 30px;
                        display: inline-block;
                        .section-title{
                            float: left;
                        }
                        .thebtn{
                            float: left;
                            margin: 5px;
                        }
                    }
                }
            }
        }
    }
    .btn-theme{
        background-color: $themeColor;
        border-color: $themeColor;
        color: white;
    }
    .btn-purple, .txt-purple{
        color: $themeColor;
    }
</style>
