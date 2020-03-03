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
                            <form action="{{ route('manager_affiche.affiche.affiche_view_list') }}" method="get"  id="add-building-form">
                                <div class="pull-left col-3">
                                    <label>关键词</label>
                                    <input type="text" class="el-input__inner col-10" value="{{ Request::get('keywords') }}" placeholder="手机号" name="keywords">
                                </div>
                                <input type="hidden" name="icheid" value="{{ Request::get('icheid') }}">
                                <button class="btn btn-primary">搜索</button>

                                <?php
                                Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                                ?>

                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle text-center">
                                <thead>
                                <tr>
                                    <th>昵称</th>
                                    <th>姓名</th>
                                    <th>手机号</th>
                                    <th>浏览时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataList as $key=>$val)
                                    <tr>
                                        <td>{{ $val->nice_name }}</td>
                                        <td>{{ $val->name }}</td>
                                        <td>{{ $val->mobile }}</td>
                                        <td>{{ $val->created_at }}</td>
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
