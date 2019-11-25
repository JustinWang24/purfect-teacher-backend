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
                        <div class="form-group">
                            <label for="application-reason-text">申请理由</label>&nbsp&nbsp&nbsp&nbsp
                            <textarea name="" id="" cols="30" rows="10" class="form-control">
                                {{$application->reason}}
                            </textarea>
                        </div>


                         <div class="form-group">
                             <label for="application-type-input">申请类型</label>
                             <select name="" id="application-type-select" type="select" class="form-control">
                                 <option value="">{{$application->applicationType->name}}</option>
                             </select>
                         </div>
                        <div class="form-group">
                             <label for="application-type-input">申请时间</label>&nbsp&nbsp&nbsp&nbsp
                            <input type="text" class="form-control" value="{{$application->created_at}}">
                        </div>

                        <div class="form-group">
                            <label for="application-status-radio">状态</label>&nbsp&nbsp&nbsp&nbsp
                             <input type="radio" class="form-control-radio" id="application-status-radio-close" value="1"  name="status"
                                   @if($application['status'] == 1) checked @endif> 通过  &nbsp&nbsp&nbsp&nbsp
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
