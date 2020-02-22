@extends('layouts.app')
@section('content')
<div id="teacher-assistant-check-in-app">
    <div class="blade_title">签到</div>
    <el-row :gutter="20">
        <el-col :span="12">
            <div class="grid-content bg-purple-dark"></div>
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        签到记录
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
                    <el-tree
                            :data="data"
                            :props="defaultProps"
                            :highlight-current="false"
                            icon-class="el-icon-arrow-right"
                            @node-click="">
                        <div class="custom-tree-node" slot-scope="{ node, data }">
                            <span v-html="node.label"></span>
                            <span class="showDetail" v-if="node.level==2">
                               <img @click="showDetail(node)" src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                            </span>
                        </div>
                    </el-tree>
                </div>
            </div>
        </el-col>
        <el-col :span="12">
            <div class="grid-content bg-purple-dark" v-show="ifShow">
                <div class="card">
                    <div class="card-head">
                        <header class="full-width">
                            记录详情
                        </header>
                    </div>
                    <div class="card-body">
                        <el-table
                                :data="tableData">
                            <el-table-column
                                    prop="stuName"
                                    label="姓名">
                            </el-table-column>
                            <el-table-column
                                    prop="stuNor"
                                    label="已签到">
                            </el-table-column>
                            <el-table-column
                                    prop="stuHoli"
                                    label="请假">
                            </el-table-column>
                            <el-table-column
                                    prop="stuOff"
                                    label="旷课">
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
