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
                    <header>用户信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">姓名：</label>
                                {{ $dataOne->user->name }}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">手机号：</label>
                                {{ $dataOne->user->mobile }}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">手机号：</label>
                                {{ $dataOne->user->mobile }}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">学校：</label>
                                {{ $dataOne->school->name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="card-head">
                    <header>动态信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">类型：</label>
                                {{ $afficheTypeArr[$dataOne->iche_type] }}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">内容：</label>
                                {{ $dataOne->iche_content }}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">发布时间：</label>
                                {{ $dataOne->created_at }}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">状态：</label>
                                {{ $afficheStatusArr[$dataOne->status] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--待处理数据-->
            @if($dataOne->status == -1)
                <form action="{{ route('manager_affiche.affiche.affiche_check_one') }}" method="post"  id="add-building-form">
                    @csrf
                    <div class="form-group">
                        <label for="school-name-input">故障分类</label>
                        <select id="status" class="form-control" name="status"  required>
                            <option value="0">---请选择---</option>
                            <option value="1" >通过</option>
                            <option value="2">不通过</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="building-name-input">处理结果</label>
                        <textarea  class="form-control" name="iche_checkdesc" cols="30" rows="10" placeholder=""></textarea>
                    </div>
                    <input type="hidden" name="icheid" value="{{ $dataOne->icheid }}">
                   <?php
                   Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                   ?>
                </form>
            @endif

            <!--已处理-->
            @if(in_array($dataOne->status,[1,2]))
                <div class="card-box">
                    <div class="card-head">
                        <header>处理信息</header>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">处理人：</label>
                                    {{ $dataOne->houtai_operatename }}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">处理时间：</label>
                                    {{ $dataOne->iche_checktime }}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">处理内容：</label>
                                    {{ $dataOne->iche_checkdesc }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
