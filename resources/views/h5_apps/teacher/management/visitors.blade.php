@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $schoolId }}" data-visitors="{{ json_encode($visitors) }}"></div>
    <div id="school-teacher-management-visitors-app" class="school-intro-container">
        <div class="main p-15">
            <h2>{{ $pageTitle }}</h2>
            <el-table
                    :data="visitors"
                    empty-text="今日无访客"
                    stripe
                    style="width: 100%">
                <el-table-column
                        label="预约时间">
                    <template slot-scope="scope">
                        <el-button @click="showDetail(scope.row)" type="text">@{{ scope.row.facility_name }}</el-button>
                    </template>
                </el-table-column>
                <el-table-column
                        label="访客姓名">
                    <template slot-scope="scope">
                        @{{ scope.row.building ? scope.row.building.name : null }}: @{{ scope.row.room ? scope.row.room.name : null }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="邀请人" width="80">
                    <template slot-scope="scope">
                        <span class="text-blue" v-if="scope.row.status === 1">开启</span>
                        <span class="text-danger" v-if="scope.row.status === 0">关闭</span>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <el-dialog title="设备详情" :visible.sync="showDetailFlag" fullscreen>
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
