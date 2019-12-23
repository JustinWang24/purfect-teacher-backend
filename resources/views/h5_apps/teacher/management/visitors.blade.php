@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $schoolId }}" data-visitors="{{ json_encode($visitors) }}"></div>
    <div id="school-teacher-management-visitors-app" class="school-intro-container">
        <div class="main p-15">
            <h2>{{ $pageTitle }} <el-button class="pull-right" @click="addVisitor" icon="el-icon-plus" size="mini" type="primary">添加访客</el-button></h2>
            <el-table
                    :data="visitors"
                    empty-text="今日无访客"
                    stripe
                    style="width: 100%">
                <el-table-column
                        label="预约时间">
                    <template slot-scope="scope">
                        <el-button @click="showDetail(scope.row)" type="text">@{{ scope.row.scheduled_at }}</el-button>
                    </template>
                </el-table-column>
                <el-table-column
                        label="访客姓名">
                    <template slot-scope="scope">
                        @{{ scope.row.name }}(@{{ scope.row.mobile }})
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

        <el-dialog title="访客详情" :visible.sync="showDetailFlag" fullscreen>
            <h4 class="title">设备名称: @{{ selectedDevice.facility_name }}</h4>
            <ul>
                <li>
                    <p class="text-dark">设备编号: @{{ selectedDevice.facility_number }}</p>
                </li>
                <li>
                    <p class="text-dark">室内位置: @{{ selectedDevice.detail_addr }}</p>
                </li>
                <li>
                    <p class="text-dark">建筑: @{{ selectedDevice.building ? selectedDevice.building.name : null }}</p>
                </li>
                <li>
                    <p class="text-dark">房间: @{{ selectedDevice.room ? selectedDevice.room.name : null }}</p>
                </li>
                <li>
                    <p class="text-dark">记录更新: @{{ selectedDevice.updated_at }}</p>
                </li>
            </ul>
        </el-dialog>
    </div>
@endsection
