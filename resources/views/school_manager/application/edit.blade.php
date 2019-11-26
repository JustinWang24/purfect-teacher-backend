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
                    <header>编辑</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.students.applications-edit') }}" method="post"  id="add-application-type-form">
                        @csrf
                        <input type="hidden" id="type-id" name="id" value="{{$application->id}}">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-name">姓名:</label>
                                    {{$application->user->name}}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-profile-gender">性别:</label>
                                    {{$application->user->profile->gender_text}}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-profile-idNumber">身份证:</label>
                                    {{$application->user->profile->id_number}}
                                </div>
                            </div>


                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-profile-nationName">民族:</label>
                                    {{$application->user->profile->nation_name}}
                                </div>
                            </div>


                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-user-mobile">联系电话:</label>
                                    {{$application->user->mobile}}
                                </div>
                            </div>



                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-profile-studentNumber">学号:</label>
                                    {{$application->user->profile->student_number}}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-profile-">学制:</label>
                                    {{ $application->user->gradeUser->major->period }}年
                                </div>
                            </div>


                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-major-name">专业:</label>
                                    {{$application->user->gradeUser->major->name}}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-grade-name">班级:</label>
                                    {{$application->user->gradeUser->grade->name}}
                                </div>
                            </div>




                            @if(!empty($application->census))

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-census">家庭户口:</label>&nbsp&nbsp&nbsp&nbsp
                                    {{$application->census_text}}
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-family-population">家庭人口总数:</label>
                                    {{$application->family_population}} 人
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-general-income">家庭月总收入:

</label>
                                    {{$application->general_income}}元
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-per-capita-income">人均月收入:
</label>
                                    {{$application->per_capita_income}}元
                                </div>
                            </div>

<div class="col-3">
                                <div class="form-group">
                                    <label for="application-per-capita-income">收入来源:
</label>
                                    {{$application->income_source}}
                                </div>
                            </div>

                            @endif

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="application-profile-nationName">家庭住址:</label>
                                    {{$application->user->profile->state}}省{{$application->user->profile->city}}市{{$application->user->profile->area}}{{$application->user->profile->address_line}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="application-reason-text">申请理由</label>&nbsp;&nbsp;&nbsp;&nbsp;
                            <p class="text-info">
                                {{$application->reason}}
                            </p>
                        </div>

                         <div class="form-group">
                             <label for="application-type-input">申请类型</label>
                             <select disabled id="application-type-select" type="select" class="form-control">
                                 <option value="">{{$application->applicationType->name}}</option>
                             </select>
                         </div>
                        <div class="form-group">
                             <label for="application-type-input">申请时间</label>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input disabled type="text" class="form-control" value="{{$application->created_at}}">
                        </div>

                        <div class="form-group">
                            <label for="application-status-radio">状态</label>&nbsp;&nbsp;&nbsp;&nbsp;
                             <input type="radio" class="form-control-radio" id="application-status-radio-close" value="1"  name="status"
                                   @if($application['status'] == 1) checked @endif> 通过  &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="form-control-radio" id="application-status-radio-open"  value="2"  name="status"
                                   @if($application['status'] == 2) checked @endif> 拒绝
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-application','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
 </div>
@endsection
