<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        编辑"{{ $profile->user->name }}"的教职工档案
                        <a href="{{ route('school_manager.school.teachers') }}" class="btn btn-primary">返回教职工档案管理</a>
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.profile.modify') }}" method="post">
                        @csrf
                        <input type="hidden" name="profile[uuid]" value="{{ $profile->uuid }}">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>姓名</label>
                                    <input required type="text" class="form-control" value="{{ $profile->user->name }}" placeholder="教职工姓名" name="teacher[name]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>手机号码</label>
                                    <input required type="text" class="form-control" value="{{ $profile->user->mobile }}" placeholder="手机号码" name="teacher[mobile]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>身份证号</label>
                                    <input required type="text" class="form-control" value="{{ $profile->id_number }}" placeholder="身份证号" name="profile[id_number]">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label>教师编号</label>
                                    <input type="text" class="form-control" value="{{ $profile->serial_number }}" placeholder="选填: 教师编号" name="profile[serial_number]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>政治面貌</label>
                                    <input required type="text" class="form-control" value="{{ $profile->political_name }}" placeholder="政治面貌" name="profile[political_name]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>民族</label>
                                    <input required type="text" class="form-control" value="{{ $profile->nation_name }}" placeholder="民族" name="profile[nation_name]">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label>出生年月</label>
                                    <input required type="date" class="form-control" value="{{ $profile->birthday }}" placeholder="出生年月" name="profile[birthday]">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4>教育/工作履历</h4>
                        <hr>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>参加工作时间</label>
                                    <input required type="text" class="form-control" value="{{ $profile->work_start_at }}" placeholder="参加工作时间" name="profile[work_start_at]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>第一学历</label>
                                    <input required type="text" class="form-control" value="{{ $profile->education }}" placeholder="第一学历" name="profile[education]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>第一学历专业</label>
                                    <input required type="text" class="form-control" value="{{ $profile->major }}" placeholder="第一学历专业" name="profile[major]">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>最高学历</label>
                                    <input required type="text" class="form-control" value="{{ $profile->final_education }}" placeholder="最高学历" name="profile[final_education]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>最高学历专业</label>
                                    <input required type="text" class="form-control" value="{{ $profile->final_major }}" placeholder="最高学历专业" name="profile[final_major]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>学位</label>
                                    <input required type="text" class="form-control" value="{{ $profile->degree }}" placeholder="学位" name="profile[degree]">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>职称取得时间</label>
                                    <input required type="text" class="form-control" value="{{ $profile->title_start_at }}" placeholder="职称取得时间" name="profile[title_start_at]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>是否聘任</label>
                                    <select required type="date" class="form-control" name="profile[hired]">
                                        <option {{ $profile->hired ? 'selected':null }} value="1">是</option>
                                        <option {{ !$profile->hired ? 'selected':null }} value="0">否</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>聘任时间</label>
                                    <input required type="text" class="form-control" value="{{ $profile->hired_at }}" placeholder="聘任时间" name="profile[hired_at]">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>原职称取得时间</label>
                                    <input required type="text" class="form-control" value="{{ $profile->title1_at }}" placeholder="原职称取得时间" name="profile[title1_at]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>原职称聘任时间</label>
                                    <input required type="text" class="form-control" value="{{ $profile->title1_hired_at }}" placeholder="原职称聘任时间" name="profile[title1_hired_at]">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>现任专业技术职务名称</label>
                                    <input required type="text" class="form-control" value="{{ $profile->title }}" placeholder="现任专业技术职务名称" name="profile[title]">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>备注</label>
                            <textarea class="form-control" placeholder="选填: 备注" name="profile[notes]">{{ $profile->notes }}</textarea>
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
