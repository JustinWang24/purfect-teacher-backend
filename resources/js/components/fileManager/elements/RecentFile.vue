<template>
    <div class="recent-file-wrapper" v-on:click="itemClicked">
        <div class="star-wrap">
            <i class="el-icon-star-on" v-if="file.star" v-on:click="starClicked"></i>
            <i class="el-icon-star-off" v-if="!file.star"></i>
            <more-actions class="txt-white m--10" :file="file" color="rgb(98, 109,183)"></more-actions>
        </div>
        <p class="updated-at">{{ file.updated_at }}</p>
        <p class="file-name">{{ file.name }}</p>
    </div>
</template>

<script>
    import MoreActions from './MoreActions';

    export default {
        name: "RecentFile",
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
    $colorWhite: white;
    $colorGrey: rgb(166, 174, 233);
    .recent-file-wrapper{
        background-color: $colorWhite;
        border-radius: 8px;
        color: $themeColor !important;
        width: 130px;
        height: 160px;
        margin-bottom: 16px;
        overflow: hidden;
        padding: 10px;
        -webkit-box-shadow: 10px 10px 24px -16px #171517;
        -moz-box-shadow: 10px 10px 24px -16px #171517;
        box-shadow: 10px 10px 24px -16px #171517;
        &:hover{
            background-color: #e8e8e8;
        }
        .star-wrap{
            display: flex;
            justify-content: space-between;
            i{
                font-size: 24px;
            }
            .m--10{
                margin-top: 0;
            }
            .txt-white{
                color: white;
                div{
                    color: white;
                }
            }
        }
        .updated-at{
            color: $colorGrey;
            font-size: 12px;
            margin-top: 30px;
        }
        .file-name{
            font-size: 15px;
            line-height: 24px;
        }
    }
</style>