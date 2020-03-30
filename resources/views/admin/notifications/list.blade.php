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
          <header>{{ $pageTitle }}</header>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 mb-2">
              <form action="{{ route('admin.notifications.list') }}" method="get"  id="add-building-form">
                <div class="pull-left col-3">
                  <label>关键词</label>
                  <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="标题" name="keywords">
                </div>
                <button class="btn btn-primary">搜索</button>
                <a href="{{ route('admin.notifications.add') }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                  添加 <i class="fa fa-plus"></i>
                </a>
              </form>

            </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>用户</th>
                  <th>标题</th>
                  <th>添加时间</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataList as $key=>$val)
                  <tr>
                    <td>{{ $val->id }}</td>
                    <td>
                      @if($val->to == '0')所有用户@endif
                      @if($val->to == '-1')教师用户@endif
                      @if($val->to == '-2')学生用户@endif
                    </td>
                    <td>{{ str_limit($val->title, 60, '...') }}</td>
                    <td>{{ $val->created_at }}</td>
                    <td class="text-center">
                      {{ Anchor::Print(['text'=>'编辑','class'=>'btn btn-primary','href'=>route('admin.notifications.edit',['uuid'=>$val['uuid']])], Button::TYPE_DEFAULT,'edit') }}
                      {{ Anchor::Print(['text'=>'删除','class'=>'btn-delete-room btn-need-confirm','href'=>route('admin.notifications.delete',['uuid'=>$val['uuid']])], Button::TYPE_DANGER,'trash') }}
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
@endsection
