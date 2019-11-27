<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在学校 ({{ session('school.name') }}) 搜索学生或老师</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.attendance.person.search', ['id'=>$taskId]) }}" method="get"  id="search-attendance-form">
                        @csrf
                        <div class="row">
                            <label for="attendance-title-input">查询</label>
                            <div class="dataTables_length" id="example4_length">
                                <label>分类<select name="search[type]" class="form-control"><option value="1">教师</option><option value="2">学生</option></select>
                                </label>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div id="example4_filter" class="dataTables_filter">
                                    <label> _<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example4" value="{{ $search['keyword'] }}" placeholder="查询" name="search[keyword]"></label>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">查询</button>
                    </form>
                </div>
                @if($result)
                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>人名</th>
                                    <th>身份</th>
                                    <th>院系</th>
                                    <th>专业</th>
                                    <th style="width: 500px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($result as $index=>$person)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $person->name }}</td>
                                        <td>
                                            {{$userProfile[$person->user_id]['slug']}}
                                        </td>
                                        <td>
                                            {{$userProfile[$person->user_id]['department']}}

                                        </td>
                                        <td class="text-center">
                                            {{$userProfile[$person->user_id]['major']}}
                                        </td>
                                        <td>
                                            <div class="row">
                                                <form action="{{ route('school_manager.attendance.person.add', ['id'=>$taskId]) }}" method="get"  id="search-attendance-form">
                                                    @csrf
                                                    <input type="hidden" name="personId" value="{{ $person->user_id }}">
                                                <div class="dataTables_length" id="example5_length">
                                                    <label>周期
                                                            <select name="week" class="form-control">
                                                                <option value="1">周一</option>
                                                                <option value="2">周二</option>
                                                                <option value="3">周三</option>
                                                                <option value="4">周四</option>
                                                                <option value="5">周五</option>
                                                                <option value="6">周六</option>
                                                                <option value="0">周日</option>
                                                            </select>
                                                    </label>
                                                </div>
                                                <div class="dataTables_length" id="example5_length">
                                                    <label>时间
                                                        <select name="slotId" class="form-control">
                                                            @foreach($timeSlots as $timeSlot)
                                                            <option value="{{$timeSlot->id}}">{{$timeSlot->title}}</option>
                                                            @endforeach
                                                        </select>
                                                    </label>
                                                </div>
                                                    <button type="submit" class="btn btn-primary">添加</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
