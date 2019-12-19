<template>
    <div class="notice-item" @click="onItemClicked">
        <el-row>
            <el-col :span="16">
                <h3>{{ notice.title }}</h3>
                <p><i class="el-icon-alarm-clock"></i>&nbsp;<span>发布时间: {{ notice.release_time }}</span></p>
            </el-col>
            <el-col :span="8">
                <img :src="notice.image" style="width: 100%;">
            </el-col>
        </el-row>

    </div>
</template>

<script>
    export default {
        name: "NoticeItem",
        props:['notice'],
        data(){
            return{}
        },
        methods:{
            onItemClicked: function () {
                this.$emit('notice-clicked',{item:this.notice.id});
            }
        }
    }
</script>

<style scoped lang="scss">
.notice-item{
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