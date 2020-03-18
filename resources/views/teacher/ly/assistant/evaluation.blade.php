@extends('layouts.app')
@section('content')
<div id="teacher-assistant-evaluation-app">
    <div class="blade_title">评分</div>
    <el-row :gutter="20">
        <el-col :span="8">
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
                            <el-button type="primary" size="small" @click="searchList()">查询</el-button>
                        </div>
                    </header>
                </div>
                <div class="card-body">
                    <el-tree
                            v-show="data.length > 0"
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
                     <div v-show="data.length == 0" class="no-data-img">
                         <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                         <p>当前列表暂时没有数据哦~</p>
                     </div>
                </div>
            </div>
        </el-col>
        <el-col :span="8">
            <div class="grid-content bg-purple-dark" v-show="ifShow">
                <div class="card">
                    <div class="card-head">
                        <header class="full-width">
                            记录详情
                        </header>
                    </div>
                    <div class="card-body">
                        <el-table
                                v-show="tableData.length > 0"
                                :show-header="false"
                                :data="tableData">
                            <el-table-column
                                    prop="name"
                                    label="姓名">
                            </el-table-column>
                            <el-table-column
                                    prop="score"
                                    label="平均分">
                                <template slot-scope="scope">
                                    <span style="color:#8A93A1">平均分</span>
                                    <span v-text="scope.row.score"></span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                    label="备注">
                                <template slot-scope="scope">
                                    <a href="javascript:;" v-if="scope.row.status" style="color: #4EA5FE;" @click="showNote(scope.row)">备注</a>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div v-show="tableData.length == 0" class="no-data-img">
                            <img src="{{ asset('assets/img/teacher_blade/no-data.png') }}" alt="">
                            <p>当前列表暂时没有数据哦~</p>
                        </div>
                    </div>
                </div>
            </div>
        </el-col>
        <el-col :span="8">
            <div class="grid-content bg-purple-dark" v-show="ifShow && ifShowNote">
                <div class="card">
                    <div class="card-head">
                        <header class="full-width">
                            备注信息
                            <span style="float: right" v-text="stuName"></span>
                        </header>
                    </div>
                    <div class="card-body">
                        <div v-show="nodeData.length > 0" class="eva_note" v-for="(item, i) in nodeData">
                            <div class="eva_noteTitle" v-html="item.time + ' ' + item.timeSlot"></div>
                            <div class="eva_noteText" v-html="item.remark"></div>

                        </div>
                        <div v-show="nodeData.length == 0" class="no-data-img">
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
