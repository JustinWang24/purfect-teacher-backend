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
                            <form action="{{ route('manager_affiche.affiche.affiche_pending_list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-3">
                                    <label>关键词</label>
                                    <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="手机号" name="keywords">
                                </div>
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
                                    <th>姓名</th>
                                    <th>手机号</th>
                                    <th>学校</th>
                                    <th width="20%">内容</th>
                                    <th>分享总数</th>
                                    <th>点赞人数</th>
                                    <th>评论人数</th>
                                    <!--th>状态</th-->
                                    <th>添加时间</th>
                                    <th width="20%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->icheid }}</td>
                                        <td>{{ $val->user->name }}</td>
                                        <td>{{ $val->user->mobile }}</td>
                                        <td>{{ $val->school->name }}</td>
                                        <td>{{ $val->iche_content}}</td>
                                        <td>{{ $val->iche_share_num }}</td>
                                        <td>{{ $val->iche_praise_num }}</td>
                                        <td>{{ $val->iche_comment_num }}</td>
                                        <!--td>{{ $afficheStatusArr[$val->status] }}</td-->
                                        <td>{{ $val->created_at }}</td>
                                        <td class="text-center">
											{{ Anchor::Print(['text'=>'评论','class'=>'btn btn-primary','href'=>route('manager_affiche.affiche.affiche_comment_list',['icheid'=>$val->icheid])], Button::TYPE_DEFAULT,'') }}
											{{ Anchor::Print(['text'=>'点赞','class'=>'btn btn-primary','href'=>route('manager_affiche.affiche.affiche_praise_list',['icheid'=>$val->icheid])], Button::TYPE_DEFAULT,'') }}
                                            {{ Anchor::Print(['text'=>'详情','class'=>'btn btn-primary','href'=>route('manager_affiche.group.group_affiche_one',['icheid'=>$val->icheid])], Button::TYPE_DEFAULT,'') }}
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
</script>
@endsection
