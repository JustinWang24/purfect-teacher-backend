<template>
    <div class="plan-list-wrap">
        <el-table
                :data="plans"
                stripe
                style="width: 100%">
            <el-table-column
                    label="专业">
                <template slot-scope="scope">
                    <p>{{ scope.row.major_name}}</p>
                    <p>{{ typeText(scope.row.type) }}</p>
                </template>
            </el-table-column>
            <el-table-column
                    prop="title"
                    label="标题">
            </el-table-column>
            <el-table-column
                    prop="start_at"
                    label="开始日期">
            </el-table-column>
            <el-table-column
                    label="招生人数/统计">
                <template slot-scope="scope">
                    <p>{{ scope.row.seats }}人/{{ scope.row.grades_count }}班</p>
                    <p>{{ scope.row.applied_count }}报名/{{ scope.row.enrolled_count }}录取</p>
                </template>
            </el-table-column>
            <el-table-column
                    label="操作">
                <template slot-scope="scope">
                    <el-button-group>
                        <el-button size="mini" type="primary" icon="el-icon-edit"></el-button>
                        <el-button size="mini" type="danger" icon="el-icon-delete"></el-button>
                    </el-button-group>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';
    import axios from 'axios';

    export default {
        name: "RecruitmentPlansList",
        props:[
            'schoolId','userUuid','canDelete','year'
        ],
        data(){
            return {
                plans:[],
                reloading: false,
                pageNumber:0,
                pageSize:20,
            }
        },
        created() {
            this.loadPlans();
        },
        methods: {
            loadPlans: function(){
                axios.post(
                    Constants.API.RECRUITMENT.LOAD_PLANS,
                    {school: this.schoolId, pageNumber: this.pageNumber, pageSize: this.pageSize, year: this.year}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.plans = res.data.data.plans;
                    }
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