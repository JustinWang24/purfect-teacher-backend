<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header></header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <form action="{{ route('manager_affiche.group.group_notice_list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-3">
                                    <label>关键词</label>
                                    <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="内容" name="keywords">
                                </div>
                                <input type="hidden" name="groupid" value="{{ Request::get('groupid') }}">
                                <button class="btn btn-primary">搜索</button>

                                <a href="{{ route('manager_affiche.group.group_adopt_list') }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>

                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>内容</th>
                                    <th>已读数</th>
                                    <th>添加时间</th>
                                    <th>状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->noticeid }}</td>
                                        <td>{{ $val->notice_content }}</td>
                                        <td>{{ $val->notice_number1 }}</td>
                                        <td>{{ $val->created_at }}</td>
                                        <td>{{ $noticeStatusArr[$val->status] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $dataList->appends(Request::all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
