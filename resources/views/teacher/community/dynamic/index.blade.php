<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>名称</th>
                                    <th>学校</th>
                                    <th>分享</th>
                                    <th>点赞</th>
                                    <th>添加时间</th>
                                    <th>推荐</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                @foreach($list as $key => $val)
                                    <tbody>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->user->name }}</td>
                                        <td>{{ $val->school->name }}</td>
                                        <td> 0 </td>
                                        <td>{{ $val->countLikeNum() }}</td>
                                        <td>{{ $val->created_at }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
