<template>
    <div class="plan-list-wrap">
        <el-table
                :data="plans"
                stripe
                style="width: 100%">
            <el-table-column
                    label="专业">
                <template slot-scope="scope">
                    <el-badge v-if="scope.row.hot" value="热门" style="margin-top:10px;">
                        {{ scope.row.major_name}}
                    </el-badge>
                    <p v-else>
                        {{ scope.row.major_name}}
                    </p>
                    <p>{{ typeText(scope.row.type) }}</p>
                </template>
            </el-table-column>
            <el-table-column
                    prop="title"
                    label="标题">
            </el-table-column>
            <el-table-column
                    prop="start_at"
                    label="招生开始日期">
            </el-table-column>
            <el-table-column
                    label="招生人数/统计">
                <template slot-scope="scope">
                    <p>{{ scope.row.seats }}人 / {{ scope.row.grades_count }}个班</p>
                    <p>{{ scope.row.applied_count }}报名 / {{ scope.row.enrolled_count }}录取</p>
                </template>
            </el-table-column>
            <el-table-column
                    label="操作">
                <template slot-scope="scope">
                    <el-button-group>
                        <el-button size="mini" type="primary" icon="el-icon-edit" v-on:click="editPlan(scope.row)"></el-button>
                        <el-button size="mini" type="danger" icon="el-icon-delete" v-on:click="deletePlan(scope.row)"></el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    export default {
        name: "RecruitmentPlansList",
        props:[
            'userUuid','canDelete','plans','pageSize',
        ],
        data(){
            return {
                pageNumber:0,
            }
        },
        methods: {
            // 编辑已存在的招生计划
            editPlan: function(plan){
                this.$emit('edit-plan',{plan: plan});
            },
            // 删除已经存在的招生计划
            deletePlan: function(plan){
                this.$confirm('此操作将永久删除本招生计划数据, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$emit('delete-plan',{plan: plan});
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除操作'
                    });
                });
            },
            typeText: function (type) {
                return type === 1 ? '统招' : '自主招生';
            }
        }
    }
</script>

<style scoped>

</style>