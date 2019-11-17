<template>
    <div class="file-item-row" v-on:click="itemClicked" :class="highlight ? 'highlight' : ''">
        <div class="icon-box">
            <i class="el-icon-check" v-show="highlight"></i>
            <i class="el-icon-folder"></i>&nbsp;
            <span class="file-name-box">
                <el-button type="text" v-on:click.stop="changeCategory">
                    {{ file.name }}
                </el-button>
                <el-tag v-if="hasNew" size="mini" type="danger">有新文件</el-tag>
            </span>
        </div>
        <div class="updated-at-box">
            &nbsp;
        </div>
        <div class="size-box">
            &nbsp;
        </div>
        <div class="star-box">
            &nbsp;
        </div>
        <div class="actions-box">
            <more-actions
                    type="category"
                    :file="file"
                    color="rgb(98, 109,183)"
                    :download="false"
                    :share="false"
                    :rename="true"
                    v-on:file-updated-success="onCategoryUpdated"
                    v-on:item-removed="removedCategoryHandler"
            ></more-actions>
        </div>
    </div>
</template>

<script>
    import MoreActions from './MoreActions';
    export default {
        name: "CategoryItem",
        components:{MoreActions},
        props:{
            file: {
                type: Object,
                required: true
            },
            highlight: {
                type: Boolean,
                required: false
            },
            hasNew: {
                type: [Boolean, undefined],
                required: false
            }
        },
        methods: {
            itemClicked: function(){
                this.$emit('item-clicked',{file: this.file, clicked: 'category'});
            },
            onCategoryUpdated: function(payload){
                this.file.name = payload.file.name;
            },
            changeCategory: function(){
                this.$emit('change-category',{file: this.file, clicked: 'category'});
            },
            removedCategoryHandler: function(){
                this.$emit('category-removed',{file: this.file, clicked: 'category'});
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
            .file-name-box{
                .el-button--text{
                    font-weight: bold;
                    line-height:17px;
                    color: black;
                    padding: 0;
                }
            }
        }
        .updated-at-box, .size-box{
            font-size: 12px;
            color: $colorGrey;
            text-align: center;
        }
        .star-box{
            color: $themeColor;
            font-size: 20px;
            margin-top: -7px;
            margin-right: -10px;
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