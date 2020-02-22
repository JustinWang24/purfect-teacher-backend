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
					<div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">学院：</label>
                                {{ $dataOne->gradeUser->institute->name }}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">系：</label>
                                {{ $dataOne->gradeUser->department->name }}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">专业：</label>
                                {{ $dataOne->gradeUser->major->name }}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">班级：</label>
                                {{ $dataOne->gradeUser->grade->name }}
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
                    <div class="row">
                        @if($dataOne->iche_type == 'video')
                        <div style="float: left;margin: 10px;">
                            <span style="display: block;font-size: 15px;text-align: center;font-weight: bold;margin-bottom: 2px;">视频</span>
                            <a href="{{ $dataOne->afficheVideo->video_url }}" target="_blank">
                                <img src="{{ $dataOne->afficheVideo->cover_url }}" style="width:120px;height:120px;">
                            </a>
                        </div>
                        @endif
                        @if($dataOne->iche_type == 'image')
                            @foreach($dataOne->affichePics as $key=>$val)
                                <div style="float: left;margin: 10px;">
                                    <span style="display: block;font-size: 15px;text-align: center;font-weight: bold;margin-bottom: 2px;">图片{{$key+1}}</span>
                                    <a href="{{ $val->pics_bigurl }}" target="_blank">
                                        <img src="{{ $val->pics_smallurl }}" style="width:120px;height:120px;">
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!--待处理数据-->
            @if($dataOne->status == -1)
                <form action="{{ route('manager_affiche.affiche.affiche_check_one') }}" method="post"  id="add-building-form">
                    @csrf
                    <div class="form-group">
                        <label for="school-name-input">状态</label>
                        <select id="status" class="form-control" name="status"  required>
                            <option value="">-请选择-</option>
                            <option value="1" @if( old('status') == 1 ) selected @endif >通过</option>
                            <option value="2" @if( old('status') == 2 ) selected @endif >不通过</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="school-name-input">类型</label>
                        <select id="iche_categroypid" class="form-control" name="iche_categroypid"  required>
                            <option value="">-请选择-</option>1
                            <option value="0" @if( old('iche_categroypid') == 0 ) selected @endif >其他</option>
                            <option value="1" @if( old('iche_categroypid') == 1 ) selected @endif >活动</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="building-name-input">处理结果</label>
                        <textarea  class="form-control" name="iche_checkdesc" cols="30" rows="10" placeholder="">{{ old('iche_checkdesc') }}</textarea>
                    </div>
                    <input type="hidden" name="icheid" value="{{ $dataOne->icheid }}">
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
                        <header>备注信息</header>
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
                <?php
                Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                ?>
            @endif
        </div>
    </div>
@endsection
