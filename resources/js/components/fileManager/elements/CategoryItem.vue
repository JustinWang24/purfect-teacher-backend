<template>
    <div class="file-item-row">
        <div class="icon-box">
            <i class="el-icon-folder"></i>&nbsp;
            <span class="file-name-box">{{ file.name }}</span>
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
            <more-actions :file="file" color="rgb(98, 109,183)"></more-actions>
        </div>
    </div>
</template>

<script>
    import MoreActions from './MoreActions';
    export default {
        name: "CategoryItem",
        components:{MoreActions},
        props:['file'],
        methods: {
            itemClicked: function(){
                this.$emit('item-clicked',{file: this.file})
            },
            starClicked: function () {
                this.$emit('star-clicked',{file: this.file})
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
        margin: 0;
        border-radius: 6px;
        justify-content: space-between;
        &:hover{
            background-color: white;
        }
        .icon-box{
            color: $themeColor;
            font-size: 17px;
            .file-name-box{
                font-size: 18px;
                font-weight: bold;
                line-height:23px;
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
</style>