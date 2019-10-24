<template>
    <div class="courses-list-wrap">
        <el-table
                :data="courses"
                style="width: 100%">
            <el-table-column
                    label="课程名称"
                    width="140">
                <template slot-scope="scope">
                    <i class="el-icon-book"></i>
                    <span style="margin-left: 10px">{{ scope.row.name }}</span>
                </template>
            </el-table-column>
            <el-table-column
                    label="课程编号"
                    prop="code"
                    width="140">
            </el-table-column>
            <el-table-column
                    label="适用年级"
                    prop="year"
                    width="80">
            </el-table-column>
            <el-table-column
                    label="适用学期"
                    prop="term"
                    width="80">
            </el-table-column>
            <el-table-column
                    label="学分"
                    prop="scores"
                    width="80">
            </el-table-column>
            <el-table-column
                    label="授课教师"
                    width="240">
                <template slot-scope="scope">
                    <el-tag size="medium" :key="idx" effect="plain" v-for="(t, idx) in scope.row.teachers" style="margin:2px;">{{ t.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column
                    label="关联专业"
                    width="240">
                <template slot-scope="scope">
                    <el-tag size="medium" type="info" effect="plain" :key="idx" v-for="(m,idx) in scope.row.majors" style="margin:2px;">{{ m.name }}</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                    <el-button
                            size="mini"
                            type="danger"
                            v-if="canDelete"
                            @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    export default {
        name: "CoursesList",
        props: {
            courses: { // 学校的 ID
                type: Array, required: true
            },
            canDelete: { // 是否具备删除的权限
                type: Boolean, required: false, default: false
            }
        },
        data(){
            return {
            };
        },
        methods: {
            handleDelete: function(idx, row){
                this.$emit('course-delete', {idx: idx, course: row});
            },
            handleEdit: function(idx, row){
                this.$emit('course-edit', {idx: idx, course: row});
            }
        }
    }
</script>

<style scoped lang="scss">
    .courses-list-wrap{
        padding: 10px;
    }
</style>