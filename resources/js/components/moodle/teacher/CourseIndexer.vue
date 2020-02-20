<template>
    <div class="CourseIndexerWrap">
        <el-button v-if="lectures.length === 0" v-for="index in count" :key="index" :type="buttonType(index)" @click="onClicked(index)" class="item">
            第{{ index }}课
        </el-button>
        <el-button v-if="lectures.length > 0" v-for="(lecture,idx) in lectures" :key="idx" :type="buttonType(idx+1)" @click="onLectureClicked(lecture)" class="item">
            第{{ idx+1 }}课{{ lecture ? ': '+lecture.title : null }}
        </el-button>
    </div>
</template>

<script>
    // 显示课程的一共包含了多少节课可供选择
    export default {
        name: "CourseIndexer",
        props: {
            count:{
                type: Number,
                required: true
            },
            highlight:{ // 那个应该高亮， 默认是第一个
                type: Number,
                required: false,
                default: 1
            },
            lectures:{
                type: Array,
                required: false,
                default:[]
            }
        },
        data(){
            return {

            }
        },
        created: function(){

        },
        methods:{
            buttonType: function(index){
                return index === this.highlight ? 'primary' : null;
            },
            onClicked: function(index){
                this.$emit('index-clicked',{index: index});
            },
            onLectureClicked: function(lecture){
                this.$emit('lecture-clicked',{lecture: lecture});
            }
        }
    }
</script>

<style scoped lang="scss">
.CourseIndexerWrap{
    display: flex;
    flex-wrap: wrap;
    .item{
        margin:10px;
    }
}
</style>