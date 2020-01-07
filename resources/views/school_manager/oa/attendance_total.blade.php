<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 考勤统计管理</header>
                    <a href="{{ route('school_manager.oa.attendances-export', ['current'=>$current]) }}" class="btn btn-primary pull-right">
                        导出 <i class="fa fa-plus"></i>
                    </a>
                    <a href="{{ route('school_manager.oa.attendances-total', ['current'=>$current-1]) }}" class="btn btn-primary pull-right">
                        前一周
                    </a>
                    <a href="{{ route('school_manager.oa.attendances-total', ['current'=>$current+1]) }}" class="btn btn-primary pull-right">
                        下一周
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>教师姓名</th>
                                    <th>职业分类</th>
                                    <th>本周课时</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $index=>$user)
                                    <tr>
                                        <td>{{ $user->user->name }}</td>
                                        <td>
                                            {{ $user->user->profile->category_major }}
                                        </td>
                                        <td>
                                            {{ $user->total }}
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
