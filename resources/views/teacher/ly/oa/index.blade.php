@extends('layouts.app')
@section('content')
<div class="row" id="teacher-oa-index-app">
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="teacher_oa_card" v-cloak>
            <dl class="teacher_oa_card_body" v-for="item in iconList" :key="item.name">
                <a :href="item.url">
                    <dt><img :src="item.icon" alt=""></dt>
                    <dd>@{{ item.name }}</dd>
                </a>
            </dl>
        </div>
        <p class="teacher_oa_approval">我的审批</p>
        <div class="teacher_oa_approval_content">
            <div class="teacher_oa_approval_content_left" v-cloak>
                <div v-for="item in myflows" :key="item.key" v-if="myflows">
                    <p class="name">
                        <span>@{{ item.name }}</span>
                        <span @click="close(item.key)">展开</span>
                    </p>
                    <el-divider></el-divider>
                    <div class="showFlows" v-if="open === item.key">
                        <div v-for="i in item.flows" :key="i.id" class="itemFlows ddd" @click="goCreateFlow(i)">
                            <img :src="i.icon" alt="">
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
                    <ul class="tab">
                        <li v-for="(item,index) in nav" :key="index" @click="list_click(index)" :class="{'bgred':show==index}">@{{item.tit}}</li>
                    </ul>
                    <el-input placeholder="请输入审批类型/审批人" v-model="keyword">
                        <el-button slot="append" @click="serach">搜索</el-button>
                    </el-input>
                    <div class="bottom-table" v-if="tableData.length > 0">
                        <div class="table-item" v-for="item in tableData" :key="item.id">
                            <img :src="item.avatar" alt="" width="50px" style="border-radius: 50%">
                            <span style="color: #99A0AD;">申请人：@{{ item.user_name }}</span>
                            <span style="color: #D5D7E0;">申请日期：@{{ item.created_at }}</span>
                            <span v-bind:class="{
                                'status_red': item.done == 0,
                                'status_green': item.done == 1,
                                'status_yellow': item.done == 2,
                                'status_gray': item.done == 3,
                                'status_black': item.done == 4
                            }">
                                @{{statusMap[item.done]}}</span>
                            <img src="{{ asset('assets/img/teacher_blade/eye.png') }}" class="icon-image">
                        </div>
                        <el-pagination background layout="prev, pager, next" :total="total" @current-change="handleCurrentChange"></el-pagination>
                    </div>
                    <div v-else style="color: #D5D7E0;text-align: center;padding-top: 20px;background: #fff;">暂无数据~</div>
                </div>
            </div>
        </div>
    </div>
    <flow-form ref="flowForm" />
</div>

<div id="app-init-data-holder" data-school="{{ session('school.id') }}"></div>
@endsection