<template>
    <div class="more-actions">
        <el-dropdown trigger="click">
              <span class="el-dropdown-link">
                <i class="el-icon-more" :style="{color: color}"></i>
              </span>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item v-if="rename">
                    <el-button  v-on:click="showRenameForm" type="text"><i class="el-icon-edit"></i>重命名</el-button>
                </el-dropdown-item>
                <el-dropdown-item v-if="download">
                    <el-button  v-on:click="downloadFile" type="text">
                        <i class="el-icon-download"></i> {{ downloadText }}
                    </el-button>
                </el-dropdown-item>
                <el-dropdown-item v-if="share">

                    <el-button  v-on:click="shareFile" type="text">
                        <i class="el-icon-share"></i>分享
                    </el-button>
                </el-dropdown-item>
                <el-dropdown-item>
                    <el-button v-on:click="moveFile" type="text">
                        <i class="el-icon-guide"></i>移动到 ...
                    </el-button>
                </el-dropdown-item>
                <el-dropdown-item divided>
                    <el-button v-on:click="deleteActionHandler" type="text" class="txt-danger">
                        <i class="el-icon-delete"></i>删除
                    </el-button>
                </el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>

        <el-dialog :title="title" :visible.sync="renameFormVisible">
            <el-form :model="file">
                <el-form-item label="名称">
                    <el-input v-model="file.name" autocomplete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="renameFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="renameAction">确 定</el-button>
            </div>
        </el-dialog>

        <el-dialog title="分享" :visible.sync="shareFormVisible">
            <el-form :model="form">
                <el-form-item label="分享地址">
                    <el-input :id="randomId" type="textarea" v-model="form.shareAddress"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="doCopy">拷 贝</el-button>
                <el-button @click="shareFormVisible = false">关 闭</el-button>
            </div>
        </el-dialog>

        <el-dialog title="移动文件到 ..." :visible.sync="moveFormVisible">
            <el-row>
                <el-col :span="24">
                    <p style="margin:0;" v-show="isLoading">正在加载... <i class="el-icon-loading"></i></p>
                    <p style="margin:0;">
                        <span v-if="parentCategory"><i class="el-icon-folder-opened"></i> 当前目录: {{ parentCategory.name }}</span>
                        (<el-button v-on:click="loadParent(parentCategory)" type="text">返回上一级</el-button>)
                    </p>
                    <p v-for="(cat, idx) in categories" :key="idx" style="margin:0;">
                        <el-button style="padding:4px 20px;" icon="el-icon-folder" type="text" v-on:click="loadChildren(cat)"> - {{ cat.name }}</el-button>
                    </p>
                    <p v-for="(f, idxf) in files" :key="idxf" style="margin:0;">
                        <span style="padding:4px 20px;" ><i class="el-icon-document"></i> {{ f.file_name }}</span>
                    </p>
                    <p v-if="categories !== undefined && categories.length===0 && files.length === 0">空文件夹</p>
                </el-col>
            </el-row>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" @click="doMove">确 认</el-button>
                <el-button @click="closeMoveForm">关 闭</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import { renameAction, deleteAction, loadCategory, loadParentCategory, moveFileAction } from '../../../common/file_manager';
    import { Util } from '../../../common/utils';
    import { Constants } from '../../../common/constants';

    export default {
        name: "MoreActions",
        props:['file','color','download','share','rename','initCategories','userUuid'],
        computed: {
            'title': function(){
                return '修改' + this.typeText + '名称';
            },
            'typeText': function () {
                return (this.type === Constants.TYPE_CATEGORY ? '目录' : '文件');
            },
            'downloadText': function(){
                if(Util.isImage(this.file.type) || Util.isPdfDoc(this.file.type) || Util.isVideoDoc(this.file.type) || Util.isAudioDoc(this.file.type)){
                    return '查看';
                }
                else{
                    return '下载';
                }
            }
        },
        data() {
            return {
                renameFormVisible: false,
                shareFormVisible: false,
                moveFormVisible: false,
                form:{
                    shareAddress: ''
                },
                randomId: '',
                parentCategory: null,
                categories:[],
                files:[], // 如果是最后一级目录, 则里面保存着文件的列表
                isLoading: false
            }
        },
        created: function(){

            this.categories = this.initCategories;
        },
        mounted: function(){
            this.randomId = 'abc' + Math.random() * 1000000;
        },
        methods:{
            // 重命名操作
            renameAction: function(){
                this.renameFormVisible = false;
                const type = Util.isEmpty(this.type) ? Constants.TYPE_FILE : this.type;
                renameAction(this.user, this.file, type)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                message: '修改成功!',
                                type: 'success'
                            });
                            this.$emit('file-updated-success',{file: this.file, type: type })
                        }else{
                            this.$message.error('更新失败: ' + res.data.message);
                        }
                    })
                    .catch(e => {
                        this.$message.error('系统繁忙, 请稍候再试!');
                    })
            },
            // 重命名表单显示
            showRenameForm: function(){
                this.renameFormVisible = true;
            },
            deleteActionHandler: function() {
                this.$confirm('此操作将永久删除该' + this.typeText + ', 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    const type = Util.isEmpty(this.type) ? Constants.TYPE_FILE : this.type;

                    deleteAction(this.user, this.file, type)
                        .then(res => {
                            if(Util.isAjaxResOk(res)){
                                this.$message({
                                    type: 'success',
                                    message: '删除成功!'
                                });
                                this.$emit('item-removed',{file: this.file, type: type})
                            }else{
                                this.$message.error('删除操作失败: ' + res.data.message);
                            }
                        })
                        .catch(e => {
                            this.$message.error('系统繁忙, 请稍候再试!');
                        })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            downloadFile: function(){
                const type = Util.isEmpty(this.type) ? Constants.TYPE_FILE : this.type;
                if(type === Constants.TYPE_FILE){
                    window.open(this.file.url, '_blank');
                }
            },
            shareFile: function(){
                this.shareFormVisible = true;
                this.form.shareAddress = document.domain + '/share-file?f=' + this.file.uuid;
            },
            doCopy: function () {
                const text = document.getElementById(this.randomId);
                text.select();
                document.execCommand('Copy');
                this.shareFormVisible = false;
                this.$message({
                    type: 'success',
                    message: '文件共享链接已经成功复制, 请使用 Ctrl 键 + v 键 进行粘贴!'
                });
            },
            // 移动到
            moveFile: function(){
                this.moveFormVisible = true;
            },
            closeMoveForm: function(){
                this.moveFormVisible = false;
                this.files = [];
            },
            doMove: function(){
                // 把当前的文件移动到 parentCategory 的位置
                moveFileAction(this.userUuid, this.parentCategory.uuid, this.file.uuid)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.$message({
                                type: 'success',
                                message: '文件已经成功移动到文件夹: ' + this.parentCategory.name
                            });
                            this.moveFormVisible = false;
                            this.files = [];
                            this.$emit('file-moved',{file: this.file, to: this.parentCategory});
                        }
                        else{
                            this.$message.error(res.data.message);
                        }
                    });
            },
            loadChildren: function(cat){
                this.files = [];
                this.isLoading = true;
                loadCategory(this.userUuid, cat.uuid)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            this.parentCategory = cat;
                            if(res.data.data.category.children.length > 0){
                                this.categories = res.data.data.category.children;
                            }else{
                                this.$notify.info({
                                    title: '消息',
                                    message: '已经是最低一级文件夹了'
                                });
                                this.files = res.data.data.category.files;
                                this.categories = [];
                            }
                        }else{
                            this.$message.error('访问的云盘目录不存在');
                        }
                        this.isLoading = false;
                    });
            },
            loadParent: function(cat){
                this.files = [];
                let uuid;
                if(cat){
                    uuid = cat.uuid;
                }
                else{
                    uuid = this.file.category_id;
                }
                loadParentCategory(this.userUuid, uuid)
                    .then(res => {
                        if(Util.isAjaxResOk(res)){
                            if(res.data.data.category.children.length > 0){
                                this.categories = res.data.data.category.children;
                                this.parentCategory = res.data.data.category.parent;
                            }
                        }else{
                            this.$message.error('访问的云盘目录不存在');
                        }
                        this.isLoading = false;
                    });
            }
        }
    }
</script>

<style scoped lang="scss">
.txt-danger{
    color: red;
}
</style>
