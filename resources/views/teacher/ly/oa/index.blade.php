@extends('layouts.app')
@section('content')
<div class="row" id="teacher-oa-index-app">
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="teacher_oa_card">
            <dl class="teacher_oa_card_body" v-for="item in iconList" :key="item.name">
                <dt><img :src="item.icon" alt=""></dt>
                <dd v-cloak>@{{ item.name }}</dd>
            </dl>
        </div>
        <p class="teacher_oa_approval">我的审批</p>
        <div class="teacher_oa_approval_content">
            <div class="teacher_oa_approval_content_left" v-cloak>
                <div v-for="item in myflows" :key="item.key" v-if="myflows">
                    <p class="name">
                        <span>@{{ item.name }}</span>
                        <span @click="close(item.key)">@{{ state }}</span>
                    </p>
                    <el-divider></el-divider>
                    <div class="showFlows" v-if="open === item.key">
                        <div v-for="i in item.flows" :key="i.id" class="itemFlows ddd"  @click="goCreateFlow(i)">
                            <img src="{{asset('assets/img/pipeline/addTo@2x.png')}}" alt="">
                            <p>@{{ i.name }}</p>
                        </div>
                    </div>
                </div>
                <div class="imgBox" v-else>
                    <img src="{{asset('assets/img/teacher_blade/approvalItem@2x.png')}}" alt="">
                    <p>当前模块暂时没有数据哦 ~</p>
                </div>
            </div>
            <div class="teacher_oa_approval_content_right" v-cloak>
                <div class="teacher_oa_approval_content_right_tabs">
                    <ul>
                        <li v-for="(item,index) in nav" :key="index" @click="list_click(index)" :class="{'bgred':show==index}">@{{item.tit}}</li>
                    </ul>
                    <el-input placeholder="请输入审批类型/审批人">
                        <el-button slot="append">搜索</el-button>
                    </el-input>
                    <div class="bottom-table">
                        <el-table :show-header="false" :data="tableData" stripe style="width: 100%">
                            <el-table-column prop="state" width="60">
                                <template slot-scope="scope">
                                    <img class="blade_listImage" v-if="scope.row.iconState" src="{{asset('assets/img/teacher_blade/qingjia@2x.png')}}" alt="">
                                    <img class="blade_listImage" v-if="!scope.row.iconState" src="{{asset('assets/img/teacher_blade/tingzhi@2x.png')}}" alt="">
                                </template>
                            </el-table-column>
                            <el-table-column prop="state" label="状态" width="110"></el-table-column>
                            <el-table-column prop="user_name" label="姓名" width="250"></el-table-column>
                            <el-table-column prop="created_at" label="日期" width="250"></el-table-column>
                            <el-table-column prop="done" width="100px">
                                <template slot-scope="scope">
                                    <span v-bind:class="{'status_red': scope.row.done == 0,'status_green': scope.row.done == 1,'status_yellow': scope.row.done == 2,'status_gray': scope.row.done == 3,'status_black': scope.row.done == 4}">@{{statusMap[scope.row.done]}}</span>
                                </template>
                            </el-table-column>
                            <el-table-column width="80px">
                                <img src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <flow-form ref="flowForm"/>
</div>

<div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
@endsection