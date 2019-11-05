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
                    <header>修改</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.facility.edit') }}" method="post"  id="add-building-form">
                        @csrf
                        <input type="hidden" id="facility-type" name="facility[type]" value="{{$facility['type']}}">
                        <input type="hidden" id="facility-id" name="facility[id]" value="{{$facility['id']}}">

                        @include('school_manager.facility._from')
                        <div class="form-group">
                            <label for="building-addr-radio">状态</label>&nbsp&nbsp&nbsp&nbsp
                            <input type="radio" class="form-control-radio" id="facility-status-radio-close" value="0"  name="facility[status]"
                                   @if($facility['status'] == 0) checked @endif> 关闭  &nbsp&nbsp&nbsp&nbsp
                            <input type="radio" class="form-control-radio" id="facility-status-radio-open"  value="1"  name="facility[status]"
                                   @if($facility['status'] == 1) checked @endif> 开启


                        </div>

                        <?php
                        Button::Print(['id'=>'btn-edit-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@include('school_manager.facility._js')
@endsection
