<template>
    <div class="file-item-row highlight">
        <div class="icon-box">
            <i class="el-icon-folder"></i>&nbsp;
            <span class="file-name-box">
                <el-input
                        size="mini"
                        style="margin-top:-4px;"
                        :autofocus="true"
                        placeholder="新文件夹"
                        v-model="newCategoryName"
                        clearable
                        v-on:keyup.enter.native="submit"
                >
                    <template slot="append">
                        <el-button v-on:click="submit">保存</el-button>
                    </template>
                </el-input>
            </span>
        </div>
        <div class="actions-box">
            <i class="el-icon-delete" v-on:click="cancel"></i>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NewCategoryForm",
        props:{
            idx: {
                type: Boolean,
                required: false
            }
        },
        data(){
            return {
                newCategoryName:'新文件夹'
            };
        },
        methods: {
            // 确认添加新目录的操作
            submit: function(){
                this.$emit('category-name-confirmed',{name: this.newCategoryName});
            },
            // 取消添加新目录的操作
            cancel: function(){
                this.newCategoryName = '新文件夹';
                this.$emit('category-cancelled');
            }
        }
    }
</script>

<style scoped lang="scss">
    $themeColor: rgb(98, 109,183);
    $colorGrey: rgb(166, 174, 233);
    .file-item-row{
        display: flex;
        padding: 12px 12px 7px 12px;
        margin: 0 0 2px 0;
        border-radius: 6px;
        justify-content: space-between;
        &:hover{
            background-color: white;
        }
        .icon-box{
            color: $themeColor;
            font-size: 15px;
            width: 80%;
            display: flex;
            .file-name-box{
                flex-grow: 1;
            }
        }
        .actions-box{
            color: $themeColor;
            font-size: 20px;
            margin-top: -8px;
        }
    }
    .highlight{
        background-color: white;
    }
</style>