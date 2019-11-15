<template>
    <div class="file-item-row" v-on:click="itemClicked" :class="highlight?'highlight':''">
        <div class="icon-box">
            <i class="el-icon-check" v-show="highlight"></i>
            <i class="el-icon-document"></i>&nbsp;
            <span class="file-name-box">
                {{ file.name }}
            </span>
        </div>

        <div class="updated-at-box">
            {{ file.updated_at }}
        </div>
        <div class="size-box">
            {{ file.size }}
        </div>
        <div class="star-box">
            <i class="el-icon-star-on" v-show="file.star" v-on:click.stop="starClicked"></i>
            <i class="el-icon-star-off" v-show="!file.star" v-on:click.stop="starClicked"></i>
        </div>
        <div class="actions-box">
            <more-actions
                    :file="file"
                    color="rgb(98, 109,183)"
                    :download="true"
                    :share="true"
                    v-on:item-removed="itemRemoved"
            ></more-actions>
        </div>
    </div>
</template>

<script>
    import MoreActions from './MoreActions';

    export default {
        name: "FileItem",
        components:{MoreActions},
        props:['file','highlight'],
        methods: {
            itemClicked: function(){
                this.$emit('item-clicked',{file: this.file, clicked: 'file'})
            },
            starClicked: function () {
                this.$emit('star-clicked',{file: this.file, clicked: 'file'})
            },
            itemRemoved: function(){
                this.$emit('file-removed',{file: this.file, type: 'file'})
            }
        }
    }
</script>

<style scoped lang="scss">
    $themeColor: rgb(98, 109,183);
    $colorGrey: rgb(166, 174, 233);
    .file-item-row{
        display: flex;
        flex: 1;
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
                font-weight: bold;
                line-height:17px;
                color: black;
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