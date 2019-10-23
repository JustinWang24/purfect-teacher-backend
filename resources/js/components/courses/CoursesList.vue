<template>
    <div class="courses-list-wrap">
        <p class="courses-list-title">课程列表</p>
        <ul class="infinite-list" v-infinite-scroll="load" style="overflow:auto">
            <li v-for="theCourse in courses" class="infinite-list-item">
                <div class="course-item-card">
                    {{ theCourse.name }}
                </div>
            </li>
        </ul>
        <el-table
                :data="courses"
                style="width: 100%">
            <el-table-column
                    label="课程编号"
                    width="180">
                <template slot-scope="scope">
                    <i class="el-icon-book"></i>
                    <span style="margin-left: 10px">{{ scope.row.name }}</span>
                </template>
            </el-table-column>
            <el-table-column
                    label="课程编号"
                    prop="code"
                    width="180">
            </el-table-column>
            <el-table-column
                    label="授课教师"
                    width="300">
                <template slot-scope="scope">
                    <el-popover trigger="hover" placement="top">
                        <p>姓名: {{ scope.row.name }}</p>
                        <p>住址: {{ scope.row.address }}</p>
                        <div slot="reference" class="name-wrapper">
                            <el-tag size="medium">{{ scope.row.name }}</el-tag>
                        </div>
                    </el-popover>
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
                            @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';

    export default {
        name: "CoursesList",
        props: {
            courses: { // 学校的 ID
                type: Array, required: true
            }
        },
        data(){
            return {
            };
        },
        mounted(){
        },
    }
</script>

<style scoped lang="scss">
    .courses-list-wrap{
        padding: 10px;
    }
</style>