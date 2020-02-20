@extends('layouts.app')
@section('content')
<div id="teacher-assistant-grades-evaluations-app">
    <div class="blade_title">评分</div>
    <el-row :gutter="20">
        <el-col :span="12">
            <div class="grid-content bg-purple-dark"></div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        评分记录
                        <div class="search_filter">
                            <el-select size="small" v-model="filterValue" placeholder="请选择">
                                <el-option
                                        v-for="item in filterOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                            <el-button type="primary" size="small">查询</el-button>
                        </div>
                    </header>
                </div>
                <div class="card-body">
                    <el-table
                            :show-header="false"
                            :data="data">
                        <el-table-column
                                prop="class"
                                label="课时">
                        </el-table-column>
                        <el-table-column
                                prop="subject"
                                label="科目">
                        </el-table-column>
                        <el-table-column
                                label="是否评分">
                            <template slot-scope="scope">
                                <span v-html="scope.row.evaluation == 1?'已评分':'未评分'"></span>
                                <span v-if="scope.row.evaluation" class="showDetail">
                                     <img @click="showDetail(scope.row)"
                                          src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                                </span>
                            </template>
                        </el-table-column>
                    </el-table>
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
                                :show-header="false"
                                :data="tableData">
                            <el-table-column
                                    prop="stuName">
                            </el-table-column>
                            <el-table-column
                                    prop="stuMark"
                                    align="right">
                            </el-table-column>
                        </el-table>
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
