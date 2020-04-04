<template>
    <div class="plan-list-wrap">
        <el-table
                :data="plans"
                :empty-text="emptyTableText"
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
                    label="招生简章标题">
                <template slot-scope="scope">
                    <p>{{ scope.row.title }}</p>
                    <p>学费: {{ scope.row.fee }}元/年</p>
                </template>
            </el-table-column>
            <el-table-column
                    label="负责人">
                <template slot-scope="scope">
                    <p>招生: {{ scope.row.manager_name }}</p>
                    <p>录取: {{ scope.row.enrol_manager_name }}</p>
                </template>
            </el-table-column>
            <el-table-column
                    label="招生期限">
                <template slot-scope="scope">
                    <p>从{{ scope.row.start_at }}开始</p>
                    <p v-if="scope.row.end_at">{{ scope.row.end_at }}结束</p>
                    <p v-else>常年有效</p>
                </template>
            </el-table-column>

            <el-table-column
                    label="开学日期">
                <template slot-scope="scope">
                    <p v-if="scope.row.opening_date">{{ scope.row.opening_date }}</p>
                    <p v-else>未定</p>
                </template>
            </el-table-column>

            <el-table-column
                    label="招生人数/统计">
                <template slot-scope="scope">
                    <p>计划招收{{ scope.row.seats }}人 / {{ scope.row.grades_count }}个班</p>
                    <p>

                        <el-button v-show="scope.row.count1 > 0" type="text" v-on:click="forWaitings(scope.row)">
                            等待: {{ scope.row.count1 }}
                        </el-button>
                        <span v-show="scope.row.count1 <= 0">等待: 0</span>
                        /
                        <el-button v-show="scope.row.count2 > 0" type="text" v-on:click="openEnrolmentManager(scope.row)">
                            批准: {{ scope.row.count2 }}
                        </el-button>
                        <span v-show="scope.row.count2 <= 0">批准: 0</span>
                        /
                        <el-button v-show="scope.row.enrolled_count > 0" type="text" v-on:click="forApproved(scope.row)">
                            录取: {{ scope.row.enrolled_count }}
                        </el-button>
                        <span v-show="scope.row.enrolled_count <= 0">录取: 0</span>
                    </p>
                </template>
            </el-table-column>
            <el-table-column
                    label="操作">
                <template slot-scope="scope">
                    <el-button-group>
                        <el-button size="mini" type="primary" icon="el-icon-edit" v-on:click="editPlan(scope.row)"></el-button>
                        <el-button v-show="scope.row.applied_count > 0" size="mini" type="primary" icon="el-icon-tools" v-on:click="allRegistrations(scope.row)">
                            全部报名表
                        </el-button>
                        <el-button v-if="scope.row.applied_count === 0" size="mini" type="danger" icon="el-icon-delete" v-on:click="deletePlan(scope.row)"></el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';

    export default {
        name: "RecruitmentPlansList",
        props:[
            'userUuid','canDelete','plans','pageSize','schoolId','year'
        ],
        data(){
            return {
                pageNumber:0,
                emptyTableText: '还没有计划'
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
            },
            // 打开报名管理界面
            forWaitings: function (plan) {
                if(plan.applied_count > plan.passed_count){
                    const url = Constants.API.REGISTRATION_FORM.REGISTRATION_MANAGER + '?plan=' + plan.id + '&status=waiting';
                    window.open(url,'_self');
                }
            },
            allRegistrations: function (plan) {
                const url = Constants.API.REGISTRATION_FORM.REGISTRATION_MANAGER + '?plan=' + plan.id + '&status=all';
                window.open(url,'_self');
            },
            openEnrolmentManager: function (plan) {
                // 这个是列出所有被 pass 的申请表
                const url = Constants.API.REGISTRATION_FORM.ENROLMENT_MANAGER + '?plan=' + plan.id;
                window.open(url,'_self');
            },
            forApproved: function(plan){
                // 所有已经被录取的: 所有 approved
                const url = Constants.API.REGISTRATION_FORM.ENROLMENT_MANAGER + '?plan=' + plan.id + '&status=approved';
                window.open(url,'_self');
            },
            waitingsCount: function(plan){
                return plan.applied_count - plan.passed_count;
            },
            passedCount: function(plan){
                return plan.passed_count - plan.enrolled_count;
            }
        }
    }
</script>

<style scoped>

</style>
