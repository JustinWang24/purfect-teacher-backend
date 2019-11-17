<template>
    <div class="file-item-row" v-on:click="itemClicked" :class="highlight?'highlight':''">
        <div class="icon-box">
            <i class="el-icon-check" v-show="highlight"></i>
            <i class="el-icon-document"></i>&nbsp;
            <i v-if="loading" class="el-icon-loading"></i>&nbsp;
            <span class="file-name-box">
                {{ file.file_name }}
            </span>
        </div>
        <div class="star-box">
            <i class="el-icon-star-on" v-show="file.asterisk" v-on:click.stop="starClicked"></i>
            <i class="el-icon-star-off" v-show="!file.asterisk" v-on:click.stop="starClicked"></i>
        </div>
        <div class="updated-at-box">
            {{ file.created_at }}
        </div>
        <div class="size-box">
            {{ fileSize(file.size) }}
        </div>
        <div class="actions-box">
            <more-actions
                    :file="file"
                    color="rgb(98, 109,183)"
                    :download="true"
                    :share="true"
                    :rename="false"
                    :init-categories="initCategories"
                    :user-uuid="userUuid"
                    v-on:item-removed="itemRemoved"
                    v-on:file-moved="fileMovedHandler"
            ></more-actions>
        </div>
    </div>
</template>

<script>
    import MoreActions from './MoreActions';
    import { Util } from '../../../common/utils';

    export default {
        name: "FileItem",
        components:{MoreActions},
        props:['file','highlight','initCategories','userUuid'],
        data(){
            return {
                loading: false,
            }
        },
        methods: {
            itemClicked: function(){
                this.$emit('item-clicked',{file: this.file, clicked: 'file'})
            },
            starClicked: function () {
                this.$emit('star-clicked',{file: this.file, clicked: 'file'})
                this.loading = true;
                let that = this;
                window.setTimeout(function () {
                    that.loading = false;
                }, 1200)
            },
            itemRemoved: function(){
                this.$emit('file-removed',{file: this.file, type: 'file'});

                this.loading = true;
                let that = this;
                window.setTimeout(function () {
                    that.loading = false;
                }, 1200)
            },
            fileSize: function(size){
                return Util.fileSize(size);
            },
            fileMovedHandler: function(payload){
                this.$emit('file-moved',payload)
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
            width: 300px;
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