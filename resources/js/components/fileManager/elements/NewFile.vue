<template>
    <div class="new-file-wrapper">
        <div class="file-icon">
            <i class="el-icon-document"></i>
        </div>
        <div class="file-detail">
            <p class="file-name">{{ file.name }}</p>
            <p class="updated-at">{{ file.size }} {{ file.updated_at }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NewFile",
        props:['file'],
    }
</script>

<style scoped lang="scss">
    $themeColor: rgb(98, 109,183);
    $colorGrey: rgb(166, 174, 233);
    .new-file-wrapper{
        padding: 12px;
        border-radius: 8px;
        background-color: white;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        .file-icon{
            color: $themeColor;
            font-size: 24px;
            margin: 0 10px 0 10px;
        }
        .file-detail{
            .file-name{
                font-weight: bold;
                font-size: 14px;
                margin:0;
            }
            .updated-at{
                font-size: 12px;
                color: $colorGrey;
                margin:0;
                text-align: right;
            }
        }
    }
</style>