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
                                {{$dataOne['getUsersOneInfo']->name}}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">手机号：</label>
                                {{$dataOne['getUsersOneInfo']->mobile}}
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">学校：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->schools_name}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">校区：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->campuses_name}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box">
                <div class="card-head">
                    <header>报修信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">报修类型：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->typeone_name}}{{$dataOne['getWifiIssuesOneInfo']->typetwo_name}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">报修人姓名：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->issue_name}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">报修人电话：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->issue_mobile}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">报修地址：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->addr_detail}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">发布时间：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->created_at}}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="application-user-name">报修描述：</label>
                                {{$dataOne['getWifiIssuesOneInfo']->issue_desc}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!--待处理数据-->
            @if($dataOne['getWifiIssuesOneInfo']->status == 2)
                <form action="{{ route('manager_wifi.wifiIssue.update') }}" method="post"  id="add-building-form">
                    @csrf
                    <div class="form-group">
                        <label for="school-name-input">故障分类</label>
                        <select id="cityid" class="form-control" name="typeone_id"  required></select>
                    </div>
                    <div class="form-group">
                        <label for="school-name-input">故障子类</label>
                        <select id="countryid" class="form-control" name="typetwo_id"  required></select>
                    </div>
                    <div class="form-group">
                        <label for="building-name-input">处理结果</label>
                        <textarea required class="form-control" name="admin_desc" id="questionnaire-desc-input" cols="30" rows="10" placeholder="">{{ old('admin_desc') }}</textarea>
                    </div>
                    <input type="hidden" name="issueid" value="{{$dataOne['getWifiIssuesOneInfo']->issueid}}">
                   <?php
                   Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                   ?>
                </form>
            @endif

            <!--已处理-->
            @if($dataOne['getWifiIssuesOneInfo']->status == 3)
                <div class="card-box">
                    <div class="card-head">
                        <header>处理信息</header>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">处理人姓名：</label>
                                    {{$dataOne['getWifiIssuesOneInfo']->admin_name}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">接单时间：</label>
                                    {{$dataOne['getWifiIssuesOneInfo']->jiedan_time}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">处理时间：</label>
                                    {{$dataOne['getWifiIssueDisposesOneInfo']->created_at}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">故障分类：</label>
                                    {{$dataOne['getWifiIssueDisposesOneInfo']->typeone_name}}/{{$dataOne['getWifiIssueDisposesOneInfo']->typetwo_name}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">处理结果：</label>
                                    {{$dataOne['getWifiIssueDisposesOneInfo']->admin_desc}}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
           <?php
           Anchor::Print(['text'=>trans('general.return'),'href'=>route('manager_wifi.wifiIssue.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
           ?>
        </div>
    </div>
	<script src="{{ route('manager_wifi.WifiApi.get_issue_types') }}" charset="UTF-8"></script>
    <script>
        window.onload=function() {
            showLocation({{ old('typeone_id')?:0 }},{{ old('typetwo_id')?:0 }});
        }
    </script>
@endsection
