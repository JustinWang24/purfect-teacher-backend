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
                <el-button style="margin-left: 30px;" type="primary" class="btn-theme">上传本地文件<i class="el-icon-upload el-icon--right"></i></el-button>
            </div>
        </div>

        <div class="manager-body">
            <div class="sidebar-wrapper">
                <ul class="cats-group">
                    <li class="cat-item">
                        <p class="cat-name">我的文档</p>
                    </li>
                </ul>
                <ul class="cats-group">
                    <li class="cat-item">
                        <p class="cat-name">最近浏览</p>
                    </li>
                    <li class="cat-item" v-for="(recentFile, idx) in recentFiles" :key="idx">
                        <recent-file :file="recentFile"></recent-file>
                    </li>
                </ul>
            </div>
            <div class="files-list-wrapper">
                <div class="the-wrapper main-title">
                    <p class="section-title">文件列表</p>
                    <ul class="path">
                        <li class="path-item" v-for="(item, idx) in path" :key="idx">
                            &nbsp;{{ item.name }} /
                        </li>
                        <li class="path-item">当前目录: </li>
                    </ul>
                </div>
                <div class="files-wrap">
                    <category-item v-for="cat in categories" :key="cat.uuid" :file="cat"></category-item>
                    <file-item v-for="(file, idx) in returnedFiles" :key="idx" :file="file"></file-item>
                </div>

            </div>
            <div class="file-preview-wrapper">
                <div class="the-wrapper">
                    <p class="section-title">最新上传</p>
                    <new-file v-for="(file, idx) in newFiles" :key="idx" :file="file"></new-file>
                    <p class="section-title" style="margin-top: 30px;">文件预览/详情</p>
                    <el-card shadow="always">
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
    import NewFile from './elements/NewFile';

    export default {
        name: "FileManager",
        props:['userUuid'],
        components:{
            RecentFile,FileItem,CategoryItem,NewFile
        },
        data() {
            return {
                // 当前被选择的目录
                selectedCategory:{
                    subs:[],
                    files:[]
                },
                // 当前被选择的文件
                selectedFile: {
                    uuid: '123456',
                    star: true,
                    updated_at: '2019-11-15 14:24',
                    name:'打法卡萨大立科技付款但是.doc',
                    type:'doc',
                    size: '150k'
                },
                // 被加载的所有文件夹
                categories: [
                    {
                        uuid: '123456',
                        name:'文件夹 1',
                    },
                    {
                        uuid: '123456888',
                        name:'文件夹 2',
                    },
                    {
                        uuid: '123456999',
                        name:'文件夹 3',
                    },
                ],
                // 保存的当前的路径
                path:[
                    {
                        uuid: 123456,
                        name: '课堂笔记'
                    },{
                        uuid: 123456,
                        name: '第一年的文件'
                    },{
                        uuid: 123456,
                        name: '我的文档'
                    },
                ],
                // 搜索文件框的关键字
                query: '',
                // 被搜到的文件的列表
                returnedFiles: [
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
                    },
                    {
                        uuid: '1234569',
                        star: false,
                        updated_at: '2019-11-15 14:24',
                        name:'打法卡萨大立科技付款但是.png',
                        type:'png',
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
                    },
                    {
                        uuid: '1234569',
                        star: false,
                        updated_at: '2019-11-15 14:24',
                        name:'打法卡萨大立科技付款但是.png',
                        type:'png',
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
                    },
                    {
                        uuid: '1234569',
                        star: false,
                        updated_at: '2019-11-15 14:24',
                        name:'打法卡萨大立科技付款但是.png',
                        type:'png',
                        size: '150k'
                    }
                ],
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
                newFiles:[
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
                ]
            }
        },
        methods: {
            handleReturnedFileSelect: function(item) {

            },
            querySearchFilesAsync: function(queryString, cb){

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
                }
                .main-title{
                    display: flex;
                    justify-content: space-between;
                    padding-right: 12px;
                    .path{
                        display: flex;
                        flex-direction: row-reverse;
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
                        text-align: right;
                    }
                }
            }
        }
    }
    .btn-theme{
        background-color: $themeColor;
        border-color: $themeColor;
    }
</style>