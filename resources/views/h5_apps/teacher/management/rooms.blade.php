@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $teacher->getSchoolId() }}" data-rooms="{{ json_encode($rooms) }}" data-token="{{ $api_token }}"></div>
    <div id="school-teacher-management-rooms-app" class="school-intro-container">
        <div class="main p-15">
            <h2>
                <el-button type="text" icon="el-icon-arrow-left" class="text-dark" @click="back"></el-button>&nbsp;
                {{ intval($type) === \App\Models\Schools\Room::TYPE_CLASSROOM ? \App\Models\Schools\Room::TYPE_CLASSROOM_TXT.'列表' : \App\Models\Schools\Room::TYPE_MEETING_ROOM_TXT.'列表' }}</h2>
            <el-table
                    :data="rooms"
                    empty-text="还没有添加{{ $type === \App\Models\Schools\Room::TYPE_CLASSROOM ? '教室':'会议室' }}"
                    stripe
                    style="width: 100%">
                <el-table-column
                        label="房间名">
                    <template slot-scope="scope">
                        <el-button @click="showDetail(scope.row)" type="text">@{{ scope.row.name }}</el-button>
                    </template>
                </el-table-column>
                <el-table-column
                        label="容纳人数">
                    <template slot-scope="scope">
                        @{{ scope.row.seats }}
                    </template>
                </el-table-column>
                <el-table-column
                        label="建筑">
                    <template slot-scope="scope">
                        @{{ scope.row.building ? scope.row.building.name : null }}
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
@endsection
