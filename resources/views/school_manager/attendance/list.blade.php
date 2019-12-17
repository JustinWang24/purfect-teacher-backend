<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 值周任务</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            <a href="{{ route('school_manager.attendance.add') }}" class="btn btn-primary " id="btn-create-attendance-from">
                                创建新值周任务 <i class="fa fa-plus"></i>
                            </a>
                            <a href="{{ route('school_manger.school.calendar.index') }}" class="btn btn-primary">
                                查看校历
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>周次</th>
                                    <th>开始日期</th>
                                    <th>结束日期</th>
                                    <th>校领导</th>
                                    <th>中层领导</th>
                                    <th>教师</th>
                                    <th>班级</th>
                                    <th>组织单位</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($attendances as $index=>$attendance)
                                    <tr>
                                        @php
                                            $week = $configuration->getScheduleWeek($attendance->start_date, $weeks);
                                        @endphp
                                        <td>{{ $week ? $week->getName() : null }}</td>
                                        <td>{{ _printDate($attendance->start_date) }}</td>
                                        <td>{{ _printDate($attendance->end_date) }}</td>
                                        <td>{{ $attendance->high_level }}</td>
                                        <td>{{ $attendance->middle_level }}</td>
                                        <td>{{ $attendance->teacher_level }}</td>
                                        <td>
                                            {{ $attendance->grade->name }}
                                        </td>
                                        <td>
                                            @foreach($attendance->related_organizations as $org)
                                            {{ $org }}&nbsp;
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-building','href'=>route('school_manager.attendance.edit',['id'=>$attendance->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-edit-building btn-danger btn-need-confirm','href'=>route('school_manager.attendance.delete',['id'=>$attendance->id])], Button::TYPE_DEFAULT,'trash') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $attendances->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
