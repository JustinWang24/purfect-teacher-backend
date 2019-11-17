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
                        <i class="el-icon-download"></i>下载
                    </el-button>
                </el-dropdown-item>
                <el-dropdown-item v-if="share"><i class="el-icon-share"></i>分享</el-dropdown-item>
                <el-dropdown-item><i class="el-icon-guide"></i>移动到 ...</el-dropdown-item>
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
    </div>
</template>

<script>
    import { renameAction, deleteAction } from '../../../common/file_manager';
    import { Util } from '../../../common/utils';
    import { Constants } from '../../../common/constants';

    export default {
        name: "MoreActions",
        props:['file','color','download','share','type','rename'],
        computed: {
            'title': function(){
                return '修改' + this.typeText + '名称';
            },
            'typeText': function () {
                return (this.type === Constants.TYPE_CATEGORY ? '目录' : '文件');
            }
        },
        data() {
            return {
                renameFormVisible: false,
            }
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
            }
        }
    }
</script>

<style scoped lang="scss">
.txt-danger{
    color: red;
}
</style>