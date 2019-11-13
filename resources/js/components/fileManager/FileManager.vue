<template>
    
</template>

<script>
    export default {
        name: "FileManager",
        props:['userUuid'],
        data() {
            return {
                // 当前被选择的目录
                selectedCategory:{
                    subs:[],
                    files:[]
                },
                // 当前被选择的文件
                selectedFile: {

                },
                // 被加载的所有文件夹
                categories: [],
                path:[],
            }
        }
    }
</script>

<style scoped>

</style>