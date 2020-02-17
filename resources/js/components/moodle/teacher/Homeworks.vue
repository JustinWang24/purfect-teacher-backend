<template>
    <div class="homework-wrap">
        <el-table
                :data="items"
                empty-text="没有找到提交的作业"
                style="width: 100%">
            <el-table-column
                    label="日期"
                    width="180">
                <template slot-scope="scope">
                    <i class="el-icon-time"></i>
                    <span style="margin-left: 10px">{{ scope.row.created_at }}</span>
                </template>
            </el-table-column>
            <el-table-column
                    label="姓名"
                    width="180">
                <template slot-scope="scope">
                    <el-button type="text">{{ scope.row.student.name }}</el-button>
                </template>
            </el-table-column>

            <el-table-column
                    label="作业"
                    width="120">
                <template slot-scope="scope">
                    <p v-if="scope.row.media_id > 0">
                    <a :href="scope.row.media.url">查看作业</a>
                    </p>
                </template>
            </el-table-column>

            <el-table-column
                    label="作业"
                    prop="score"
                    width="80">
            </el-table-column>
            
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="check(scope.$index, scope.row)">打分</el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    export default {
        name: "Homeworks",
        props:{
            items:{
                type: Array,
                required: true
            }
        },
        data(){
            return {
            }
        },
        methods:{
            check: function (idx, item) {
                console.log('判作业',idx, item);
            }
        }
    }
</script>

<style scoped>

</style>