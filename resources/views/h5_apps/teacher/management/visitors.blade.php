@php
$nextFewDays = [];
foreach (range(0,6) as $item) {
    $nextFewDays[] = \Carbon\Carbon::now()->addDays($item);
}
@endphp

@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-token="{{ $api_token }}" data-school="{{ $schoolId }}" data-visitors="{{ json_encode($visitors) }}"></div>
    <div id="school-teacher-management-visitors-app" class="school-intro-container">
        <div class="main p-15">
            <h3>
                {{ $pageTitle }}
                <el-dropdown trigger="click" @command="handleCommand">
                    <span class="el-dropdown-link text-primary">
                        近期预约<i class="el-icon-arrow-down el-icon--right"></i>
                    </span>
                    <el-dropdown-menu slot="dropdown">
                        @foreach($nextFewDays as $next)
                        <el-dropdown-item
                            icon="el-icon-date"
                            command="{{ $next->format('Y-m-d') }}"
                        >{{ $next->format('Y年m月d日') }}</el-dropdown-item>
                        @endforeach
                    </el-dropdown-menu>
                </el-dropdown>
                <el-button class="pull-right" @click="addVisitor" icon="el-icon-date" size="mini" type="primary">添加</el-button>
            </h3>
            <el-table
                    :data="visitors"
                    empty-text="今日无访客"
                    stripe
                    style="width: 100%">
                <el-table-column label="预约时间">
                    <template slot-scope="scope">
                        <el-tag effect="dark" type="danger" v-if="scope.row.isNew" size="mini">新</el-tag>
                        <el-button @click="showDetail(scope.row)" type="text">@{{ scope.row.scheduled_at.substring(0,16) }}</el-button>
                    </template>
                </el-table-column>
                <el-table-column label="访客姓名">
                    <template slot-scope="scope">
                        @{{ scope.row.name }}(<a class="no-dec text-primary" :href="'tel:' + scope.row.mobile">@{{ scope.row.mobile }}</a>)
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="70">
                    <template slot-scope="scope">
                        <el-button type="danger" size="mini" icon="el-icon-delete" @click="deleteVisitor(scope.row)"></el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <el-dialog title="添加访客" :visible.sync="showNewVisitorFlag" fullscreen>
            <el-form ref="form" :model="newVisitor" label-width="80px">
                <el-form-item label="访客姓名" class="nb ">
                    <el-input v-model="newVisitor.name" placeholder="必填: 访客姓名"></el-input>
                </el-form-item>
                <br>
                <el-form-item label="访客电话" class="nb ">
                    <el-input v-model="newVisitor.mobile" placeholder="必填: 访客手机号码"></el-input>
                </el-form-item>
                <br>
                <el-form-item label="预约时间" class="nb">
                    <el-col :span="24" style="margin-bottom: 10px;">
                        <el-date-picker value-format="yyyy-MM-dd" type="date" placeholder="选择预约日期" v-model="newVisitor.scheduled_at.date" style="width: 100%;"></el-date-picker>
                    </el-col>
                    <el-col :span="24">
                        <el-time-picker value-format="HH:mm:ss" placeholder="选择预约时间" v-model="newVisitor.scheduled_at.time" style="width: 100%;"></el-time-picker>
                    </el-col>
                </el-form-item>
                <br>
                <el-form-item label="到达时间" class="nb ">
                    <el-col :span="24" style="margin-bottom: 10px;">
                        <el-date-picker  value-format="yyyy-MM-dd" type="date" placeholder="选择到达日期" v-model="newVisitor.arrived_at.date" style="width: 100%;"></el-date-picker>
                    </el-col>
                    <el-col :span="24">
                        <el-time-picker value-format="HH:mm:ss" placeholder="选择到达时间" v-model="newVisitor.arrived_at.time" style="width: 100%;"></el-time-picker>
                    </el-col>
                </el-form-item>
                <br>
                <el-form-item label="访客车牌" class="nb ">
                    <el-input v-model="newVisitor.vehicle_license" placeholder="选填"></el-input>
                </el-form-item>
                <br>
                <el-form-item label="到访是由" class="nb">
                    <el-input placeholder="必填: 请说明到访是由以及随行人数" type="textarea" v-model="newVisitor.reason"></el-input>
                </el-form-item>
                <br>
                <el-form-item  class="nb">
                    <el-button type="primary" @click="saveVisitor">立即创建</el-button>
                    <el-button>取消</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <el-dialog title="预约详情" :visible.sync="showDetailFlag" fullscreen>
            <h4 class="title">访客: @{{ selectedVisitor.name }}</h4>
            <ul>
                <li>
                    <p class="text-dark">联系电话: @{{ selectedVisitor.mobile }}</p>
                </li>
                <li>
                    <p class="text-dark">原因: @{{ selectedVisitor.reason }}</p>
                </li>
                <li>
                    <p class="text-dark">车辆: @{{ selectedVisitor.vehicle_license }}</p>
                </li>
            </ul>
        </el-dialog>
    </div>
@endsection
