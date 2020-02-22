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
                            <form action="{{ route('manager_affiche.affiche.top_affiche_list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-2">
                                    <label>学校</label>
                                    <select id="cityid" class="el-input__inner col-10" name="school_id">
                                        <option value="0">社区未登录推荐</option>
                                    </select>
                                </div>
                                <div class="pull-left col-3">
                                    <label>关键词</label>
                                    <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="内容" name="keywords">
                                </div>
                                <button class="btn btn-primary">搜索</button>
                                <a href="{{ route('manager_affiche.affiche.top_affiche_add') }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加 <i class="fa fa-plus"></i>
                                </a>
                            </form>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>学校</th>
                                    <th>动态ID</th>
                                    <th>内容</th>
                                    <th>添加时间</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->stickid }}</td>
                                        <td>@if($val->school_id == 0) 社区未登录推荐 @else {{ $val->school->name }}@endif</td>
                                        <td>{{ $val->stick_mixid }}</td>
                                        <td>{{ str_limit($val->stick_title, 30, '...') }}</td>
                                        <td>{{ $val->created_at }}</td>
                                        <td>
                                            <input type="text" class="input-text-c input-text" id="sort_{{ $val->stickid }}" onblur="sort({{ $val->stickid }},this.value)"
                                                   value="{{ $val->stick_order }}" style="width:100px;height:30px;" name="listorders[{{ $val->stickid }}]">
                                        </td>
                                        <td class="text-center">
											{{ Anchor::Print(['text'=>'删除','class'=>'btn btn-primary','href'=>route('manager_affiche.affiche.top_affiche_delete',['stickid'=>$val->stickid])], Button::TYPE_DEFAULT,'') }}
                                        </td>
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
<script src="{{ route('manager_wifi.WifiApi.get_school_campus') }}" charset="UTF-8"></script>
<script>
    window.onload=function() {
        showLocation({{ Request::get('school_id')?:0 }},{{ Request::get('campus_id')?:0 }});
    }

    /**
     * Func 排序
     * @param stickid  id
     * @param stick_order 排序
     */
    function sort(stickid,stick_order)
    {
        $.get("{{ route('manager_affiche.affiche.top_affiche_sort') }}", { stickid: stickid,stick_order:stick_order }, function(jsondata) {},'json');
        window.location.reload();
    }
</script>
@endsection
