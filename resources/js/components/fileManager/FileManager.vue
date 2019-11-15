<template>
    <div class="file-manager-wrapper">
        <div class="top-bar">
            <div class="file-search-bar-wrapper">
                <el-autocomplete
                        prefix-icon="el-icon-search"
                        v-model="query"
                        :fetch-suggestions="querySearchFilesAsync"
                        placeholder="搜索我的文件/目录 ..."
                        @select="handleReturnedFileSelect"
                        style="width: 58%;margin-left: 19%;"
                ></el-autocomplete>
                <el-button class="btn-purple" icon="el-icon-help" type="text" style="margin-left: 30px;">使用帮助</el-button>
                <el-button class="btn-purple" icon="el-icon-tickets" type="text">服务条款</el-button>
            </div>
        </div>

        <div class="manager-body">
            <div class="sidebar-wrapper">
                <ul class="cats-group">
                    <li class="cat-item">
                        <p class="cat-name">
                            我的文档
                        </p>
                    </li>
                </ul>
                <ul class="cats-group">
                    <li class="cat-item">
                        <p class="cat-name">最近浏览</p>
                    </li>
                    <li class="cat-item" v-for="(recentFile, idx) in recentFiles" :key="idx">
                        <recent-file
                                v-on:item-clicked="handleFileItemClicked"
                                v-on:file-removed="handleFileItemRemoved"
                                :file="recentFile"
                                :highlight="idx === idxRecentFile"
                        ></recent-file>
                    </li>
                </ul>
            </div>
            <div class="files-list-wrapper">
                <div class="the-wrapper main-title">
                    <p class="section-title">文件列表&nbsp;<i class="el-icon-loading" v-show="isLoading"></i></p>
                    <ul class="path">

                        <li class="path-item" v-for="(item, idx) in path" :key="idx">
                            &nbsp;<el-button :class="idx>0?'btn-purple':''" type="text" :disabled="idx===0">{{ item.name }} / </el-button>
                        </li>
                    </ul>
                </div>
                <div class="files-wrap">
                    <div  v-for="(cat, idx) in categories" :key="cat.uuid">
                        <category-item
                                v-on:item-clicked="handleCategoryItemClicked"
                                v-on:change-category="changeCategoryHandler"
                                v-on:category-removed="removedCategoryHandler"
                                :file="cat" :highlight="idx === idxSelectedCategory"
                        ></category-item>
                    </div>
                    <div v-for="(file, idx) in returnedFiles" :key="idx">
                        <file-item
                                v-on:item-clicked="handleFileItemClicked"
                                v-on:file-removed="handleFileItemRemoved"
                                :file="file"
                                :highlight="idx === idxReturnedFile"
                        ></file-item>
                    </div>
                </div>

                <div class="space-summary">
                    <p>
                        总文件数: <span>128</span> 占用空间: <span>57M</span>, 剩余空间: <span>443M</span>
                    </p>
                </div>
            </div>
            <div class="file-preview-wrapper">
                <div class="the-wrapper">
                    <p class="section-title">文件上传</p>
                    <p>上传到目录: {{ selectedCategory.name }}</p>
                    <div class="upload-box">
                        <el-upload
                                class="upload-demo"
                                ref="uploadForm"
                                :data="uploadFormData"
                                :multiple="false"
                                :limit="1"
                                action="/api/file/upload"
                                :on-change="onSelectFileChange"
                                :on-remove="handleRemove"
                                :file-list="newFiles"
                                :auto-upload="false">
                            <el-button slot="trigger" size="small" class="btn-theme">
                                选取文件
                            </el-button>
                            <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">
                                上传到服务器
                            </el-button>
                            <div slot="tip" class="el-upload__tip">云盘剩余空间: 477M</div>
                        </el-upload>
                        <el-form ref="form" :model="uploadFormData" label-width="80px">
                            <el-input style="margin-top: 10px;"  placeholder="选填: 文件描述关键字" v-model="uploadFormData.keywords"></el-input>
                            <el-input style="margin-top: 10px;" type="textarea" autosize v-model="uploadFormData.description" placeholder="选填: 文件说明"></el-input>
                        </el-form>
                    </div>

                    <p class="section-title" style="margin-top: 30px;">文件预览/详情</p>
                    <el-card shadow="always" v-if="selectedFile">
                        <p>文件名: {{ selectedFile.name }}</p>
                        <p>创建时间: {{ selectedFile.updated_at }}</p>
                        <p>大小: {{ selectedFile.size }}</p>
                        <p>简介: 发来的卡就是附件 ad是否连接拉第三方, 发肯定是冷风机</p>
                    </el-card>
                </div>
            </div>
        </div>

        <div class="footer-bar">

        </div>
    </div>
</template>

<script>
    import RecentFile from './elements/RecentFile';
    import FileItem from './elements/FileItem';
    import CategoryItem from './elements/CategoryItem';
    import {Util} from '../../common/utils';
    import {Constants} from '../../common/constants';
    import {
        loadCategory
    } from '../../common/file_manager';

    export default {
        name: "FileManager",
        props:['userUuid'],
        components:{
            RecentFile,FileItem,CategoryItem
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
                // 被搜到的文件的列表
                returnedFiles: [],
                recentFiles:[
                    {
                        uuid: '123456',
                        star: true,
                        updated_at: '2019-11-15 14:24',
                        name:'打法卡萨大立科技付款但是.doc',
                        type:'doc',
                        size: '150k'
                    },
                    {
                        uuid: '1234567',
                        star: false,
                        updated_at: '2019-11-15 14:24',
                        name:'打法卡萨大立科技付款但是.pdf',
                        type:'pdf',
                        size: '150k'
                    },
                    {
                        uuid: '1234568',
                        star: false,
                        updated_at: '2019-11-15 14:24',
                        name:'打法卡萨大立科技付款但是.png',
                        type:'png',
                        size: '150k'
                    }
                ],
                newFiles:[], // 文件上传
                // 当前高亮的几种类型值
                idxSelectedCategory: -1,
                idxReturnedFile: -1,
                idxRecentFile: -1,
                // 是否当前正在从服务器加载数据
                isLoading: false,
                uploadFormData: {
                    category: null,
                    user: this.userUuid,
                    keywords: '',
                    description: '',
                }
            }
        },
        created() {
            this.loadCategory();
        },
        methods: {
            handleReturnedFileSelect: function(item) {

            },
            querySearchFilesAsync: function(queryString, cb){

            },
            // 文件上传相关
            onSelectFileChange: function(file, fileList){
                console.log(file);
                console.log(fileList);

            },
            handleRemove: function(file, fileList){

            },
            submitUpload: function(){
                this.uploadFormData.category = this.selectedCategory.uuid;
                this.$refs.uploadForm.submit();
            },
            // 文件上传相关结束
            // 获取目录详情的方法
            loadCategory: function(categoryId){
                this.isLoading = true;
                loadCategory(this.userUuid, categoryId)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.categories = res.data.data.category.children;
                            this.returnedFiles = res.data.data.category.files;
                            this.selectedCategory.uuid = res.data.data.category.uuid;
                            this.selectedCategory.name = res.data.data.category.name;

                            this.path = [];
                            this.path.push(this.selectedCategory);
                            this.path.push(res.data.data.category.parent);

                        }else{
                            this.$message.error('访问的云盘目录不存在');
                        }
                        this.isLoading = false;
                    })
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
            }
        }
    }
</script>

<style scoped lang="scss">
    $themeColor: rgb(98, 109,183);
    $themeGreyColor: rgb(239, 243,248);
    .file-manager-wrapper{
        display: flex;
        flex-direction: column;
        height: 100%;
        .top-bar{
            margin-top: -87px;
            width: 97%;
        }
        .manager-body{
            display: flex;
            height: 100%;
            margin-top: 14px;
            .sidebar-wrapper{
                display: block;
                width: 15%;
                background-color: $themeColor;
                padding-top: 20px;
                .cats-group{
                    margin-top: 20px;
                    .cat-item{
                        list-style: none;
                        .cat-name{
                            font-size: 15px;
                            font-weight: bold;
                            color: white;
                        }
                    }
                }
            }
            .files-list-wrapper{
                display: block;
                width: 60%;
                border-left: rgb(149, 151, 167);
                background-color: $themeGreyColor;
                .the-wrapper{
                    padding-top: 30px;
                    padding-left: 50px;
                    padding-right: 50px;
                    .section-title{
                        font-size: 24px;
                        line-height: 40px;
                        font-weight: bold;
                    }
                    .upload-box{
                        margin-right: 30px;
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
                    padding-left: 50px;
                    width: 100%;
                    height: 480px;
                    overflow: scroll;
                }
                .space-summary{
                    padding-top: 16px;
                    p{
                        text-align: right;
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
                width: 25%;
                background-color: $themeGreyColor;
                padding-top: 30px;
                .the-wrapper{
                    padding-right: 20px;
                    padding-left: 30px;
                    .section-title{
                        font-size: 24px;
                        line-height: 40px;
                        font-weight: bold;
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
    .btn-purple{
        color: $themeColor;
    }
</style>