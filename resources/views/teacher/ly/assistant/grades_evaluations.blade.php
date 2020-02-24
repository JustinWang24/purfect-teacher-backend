@extends('layouts.app')
@section('content')
<div id="teacher-assistant-grades-evaluations-app">
    <div class="blade_title">班级评分</div>
    <el-row :gutter="20">
        <el-col :span="12">
            <div class="grid-content bg-purple-dark"></div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        评分记录
                        <div class="search_filter">
                            <el-date-picker
                              v-model="date"
                              type="date"
                              placeholder="选择日期">
                            </el-date-picker>
                            <el-select size="small" v-model="filterValue" placeholder="请选择">
                                <el-option
                                        v-for="item in filterOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                            <el-button type="primary" size="small" @click="searchList">查询</el-button>
                        </div>
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                             v-show="data.length > 0"
                            :show-header="false"
                            :data="data">
                        <el-table-column
                                prop="slot_name"
                                label="课时">
                        </el-table-column>
                        <el-table-column
                                prop="course_name"
                                label="科目">
                        </el-table-column>
                        <el-table-column
                                label="是否评分">
                            <template slot-scope="scope">
                                <span :class="{
                                       'status_blue': scope.row.status == 1}" v-html="scope.row.status == 1?'已评分':'未评分'"></span>
                                <span v-if="scope.row.status" class="showDetail">
                                     <img @click="showDetail(scope.row)"
                                          src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                                </span>
                            </template>
                        </el-table-column>
                    </el-table>
                    <div v-show="data.length == 0" class="no-data-img">
                        <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                        <p>当前列表暂时没有数据哦~</p>
                    </div>
                </div>
            </div>
        </el-col>
        <el-col :span="12">
            <div class="evaluations_table2 grid-content bg-purple-dark" v-show="ifShow">
                <div class="card">
                    <div class="card-head">
                        <header class="full-width">
                            记录详情
                            <span style="float: right" v-text="teacherName"></span>
                        </header>
                    </div>
                    <div class="card-body">
                        <el-table
                                 v-show="tableData.length > 0"
                                :show-header="false"
                                :data="tableData">
                            <el-table-column
                                    prop="username">
                            </el-table-column>
                            <el-table-column
                                    prop="score"
                                    align="right">
                            </el-table-column>
                        </el-table>
                        <div v-show="data.tableData == 0" class="no-data-img">
                            <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                            <p>当前列表暂时没有数据哦~</p>
                        </div>
                    </div>
                </div>
            </div>
        </el-col>
    </el-row>
</div>

<div id="app-init-data-holder"
     data-school="{{ session('school.id') }}"
></div>
@endsection
