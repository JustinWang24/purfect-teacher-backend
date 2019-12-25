<template>
    <div class="news-item" @click="onItemClicked">
        <h3>{{ news.title }}</h3>
        <p><i class="el-icon-alarm-clock"></i>&nbsp;<span>{{ news.updated_at }}</span></p>
    </div>
</template>

<script>
    export default {
        name: "NewsItem",
        props:['news'],
        data(){
            return{}
        },
        methods:{
            onItemClicked: function () {
                this.$emit('news-clicked',{item:this.news.id});
            }
        }
    }
</script>

<style scoped lang="scss">
.news-item{
    padding: 8px;
    border-bottom: solid 1px #e8e8e8;
    h3{
        font-weight: bold;
        font-size: 16px;
        line-height: 24px;
        color: #333333;
        margin: 0;
    }
    p{
        font-size: 10px;
        color: #cccccc;
        padding-top: 5px;
        margin:0;
    }
}
</style>