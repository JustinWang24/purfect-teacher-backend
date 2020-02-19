<template>
    <div class="row grade-table-wrap">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3>
                        <i v-show="loading" class="el-icon-loading"></i>
                        班级: {{ gradeModel.name }}
                    </h3>
                    <hr>
                    <el-table
                            :data="students"
                            empty-text="没有学生记录"
                            style="width: 100%">
                        <el-table-column
                                label="姓名"
                                width="180">
                            <template slot-scope="scope">
                                <el-button type="text">{{ scope.row.student.name }}</el-button>
                            </template>
                        </el-table-column>

                        <el-table-column
                                label="课堂出勤"
                                width="120">
                            <template slot-scope="scope">
                                <p v-if="scope.row.media_id > 0">
                                    <a :href="scope.row.media.url">查看作业</a>
                                </p>
                            </template>
                        </el-table-column>

                        <el-table-column
                                label="课后作业"
                                width="120">
                            <template slot-scope="scope">
                                <p v-if="scope.row.media_id > 0">
                                    <a :href="scope.row.media.url">查看作业</a>
                                </p>
                            </template>
                        </el-table-column>

                        <el-table-column
                                label="考试成绩">
                        </el-table-column>

                        <el-table-column
                                label="提交的申请记录">
                        </el-table-column>

                        <el-table-column label="操作">
                            <template slot-scope="scope">
                                <el-button
                                        size="mini"
                                        @click="loadStudent(scope.$index, scope.row)">打分</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    /**
     * 这个现实的是学生针对指定的教师和课程的表现
     */
    import {Util} from "../../../common/utils";
    import {loadGrade} from "../../../common/grade";

    export default {
        name: "GradeTable",
        props:{
            grade:{
                required:true,
            },
            teacher:{
                required:true,
            },
            course:{
                required:true,
            },
        },
        watch:{
            'grade': function(val){
                if(!Util.isEmpty(val)){
                    this.loadGrade();
                }
            }
        },
        data(){
            return {
                gradeModel:{},
                students:[],
                loading: false
            }
        },
        methods: {
            loadGrade: function(){
                this.loading = true;
                loadGrade(this.grade,this.teacher,this.course).then(res=>{
                    if(Util.isAjaxResOk(res)){
                        this.gradeModel = res.data.data.grade;
                        this.students = res.data.data.students;
                    }
                    this.loading = false;
                });
            },
            loadStudent: function(idx, student){

            }
        }
    }
</script>

<style scoped lang="scss">
.grade-table-wrap{

}
</style>