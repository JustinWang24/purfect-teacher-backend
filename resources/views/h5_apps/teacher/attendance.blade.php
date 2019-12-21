@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $school->uuid }}" data-attendances="{{ json_encode($attendances) }}"></div>
    <div id="school-attendances-list-app" class="school-intro-container">
        <div class="main p-15">
            <el-timeline style="padding-left: 10px;">
                <el-timeline-item v-for="(ev, idx) in attendances" :key="idx" :timestamp="ev.start_date + ' - ' + ev.end_date" placement="top">
                    <el-card>
                        <p style="float: right;">
                            <el-tag size="small">@{{ idx===0 ? '预备' : '第'+idx }}周</el-tag>
                        </p>
                        <p>
                            @{{ ev.description }}&nbsp;
                        </p>
                        <ul style="list-style: none;padding-left: 10px;">
                            <li>
                                <p>值班校领导: <span class="text-primary">@{{ ev.high_level }}</span></p>
                            </li>
                            <li>
                                <p>值班领导: <span class="text-primary">@{{ ev.middle_level }}</span></p>
                            </li>
                            <li>
                                <p>值班教师: <span class="text-primary">@{{ ev.teacher_level }}</span></p>
                            </li>
                            <li>
                                <p>组织单位:
                                    <el-tag
                                            style="margin-right: 2px;"
                                            v-for="(item, id) in ev.related_organizations"
                                            :key="id"
                                            size="mini">
                                        @{{ item }}
                                    </el-tag>
                                </p>
                            </li>
                            <li>
                                <p>班级: <span class="text-primary">@{{ ev.grade.name }} </span></p>
                            </li>
                        </ul>
                    </el-card>
                </el-timeline-item>
            </el-timeline>
        </div>
    </div>
@endsection
