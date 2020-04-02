<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div-- class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>用户信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">姓名：</label>
                                {{isset($dataOne->user->name)?$dataOne->user->name:'--'}}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">昵称：</label>
                                {{ $dataOne->user->nice_name }}
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
								{{isset($dataOne->school->name)?$dataOne->school->name:'--'}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">学院：</label>
								{{isset($dataOne->gradeUser->institute->name)?$dataOne->gradeUser->institute->name:'--'}}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">系：</label>
                                {{isset($dataOne->gradeUser->department->name)?$dataOne->gradeUser->department->name:'--'}}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">专业：</label>
                                {{isset($dataOne->gradeUser->major->name)?$dataOne->gradeUser->major->name:'--'}}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">班级：</label>
                                {{isset($dataOne->gradeUser->grade->name)?$dataOne->gradeUser->grade->name:'--'}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="card-head">
                    <header>认证信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">类型：</label>
                                {{ $groupTypeIdArr[$dataOne->group_typeid] }}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">标题：</label>
                                {{ $dataOne->group_title }}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">内容：</label>
                                {{ $dataOne->group_content }}
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
                                {{ $groupStatusArr[$dataOne->status] }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div style="float: left;margin: 10px;">
                            <span style="display: block;font-size: 15px;text-align: center;font-weight: bold;margin-bottom: 2px;">logo</span>
                            <a href="{{ $dataOne->group_pics }}" target="_blank">
                                <img src="{{ $dataOne->group_pics }}" style="width:120px;height:120px;">
                            </a>
                        </div>
                        @foreach($dataOne->collegeGroupPics as $key=>$val)
                        <div style="float: left;margin: 10px;">
                            <span style="display: block;font-size: 15px;text-align: center;font-weight: bold;margin-bottom: 2px;">认证图片{{$key+1}}</span>
                            <a href="{{ $val->pics_smallurl }}" target="_blank">
                                <img src="{{ $val->pics_bigurl }}" style="width:120px;height:120px;">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!--待处理数据-->
            @if($dataOne->status == -1)
                <form action="{{ route('manager_affiche.affiche.group_check_one') }}" method="post"  id="add-building-form">
                    @csrf
                    <div class="form-group">
                        <label for="school-name-input">状态</label>
                        <select id="status" class="form-control" name="status"  ref="">
                            <option value="">---请选择---</option>
                            <option value="1"  @if( old('status') == 1 ) selected @endif>通过</option>
                            <option value="2"  @if( old('status') == 2 ) selected @endif>不通过</option>
                        </select>
                    </div>{{ old('infos.notice_title') }}
                    <div class="form-group">
                        <label for="building-name-input">备注信息</label>
                        <textarea  class="form-control" name="authu_refusedesc" cols="30" rows="10" placeholder="">{{ old('authu_refusedesc') }}</textarea>
                    </div>
                    <input type="hidden" name="groupid" value="{{ $dataOne->groupid }}">
                    <?php
                    Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                    ?>
                    <?php
                    Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
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
                                    {{ $dataOne->group_time1 }}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">处理内容：</label>
                                    {{ $dataOne->authu_refusedesc }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        <?php
        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
        ?>
        @endif
    </div>
@endsection
