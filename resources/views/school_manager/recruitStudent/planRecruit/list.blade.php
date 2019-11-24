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
                    <header>预招列表</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                    <tr>
                                        <th>序号</th>
                                        <th>专业名称</th>
                                        <th>计划招收</th>
                                        <th>已报名</th>
                                        <th>学费</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($major as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val['name'] }}</td>
                                        <td>{{ $val['seats'] }}</td>
                                        <td></td>
                                        <td>{{ $val['fee'] }}</td>
                                        <td></td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','href'=>route('school_manager.planRecruit.edit',['majorId'=>$val['id']])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $major->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
