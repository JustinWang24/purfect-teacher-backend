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
                                {{$dataOne['getWifiIssueCommentsOneInfo']->schools_name}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">校区：</label>
                                {{$dataOne['getWifiIssueCommentsOneInfo']->campuses_name}}
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

            <div class="card-box">
                <div class="card-head">
                    <header>评价信息</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">评价时间：</label>
                                {{$dataOne['getWifiIssueCommentsOneInfo']->created_at}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">服务态度：</label>
                                {{$dataOne['getWifiIssueCommentsOneInfo']->comment_service}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">工作效率：</label>
                                {{$dataOne['getWifiIssueCommentsOneInfo']->comment_worders}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">满意度：</label>
                                {{$dataOne['getWifiIssueCommentsOneInfo']->comment_efficiency}}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="application-user-name">评论内容：</label>
                                {{$dataOne['getWifiIssueCommentsOneInfo']->comment_content}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <?php
           Anchor::Print(['text'=>trans('general.return'),'href'=>route('manager_wifi.wifiIssueComment.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
           ?>
        </div>
    </div>
    <script>
        window.onload=function() {
            showLocation({{ old('typeone_id')?:0 }},{{ old('typetwo_id')?:0 }});
        }
    </script>
@endsection
