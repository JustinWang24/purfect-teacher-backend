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
                    <form action="{{ route('school_manager.students.applications-set-save') }}" method="post"  id="add-application-type-form">
                        <input type="hidden" id="type-id" name="type[id]" value="{{$type->id}}">
                        @include('school_manager.application_type._form')

                        <div class="form-group">
                            <label for="building-addr-radio">状态</label>&nbsp&nbsp&nbsp&nbsp

                            @foreach($type->getAllStatus() as $key => $val)
                            <input type="radio" class="form-control-radio" id="facility-status-radio-close" value="{{$key}}"  name="type[status]"
                                   @if($type['status'] == $key) checked @endif> {{$val}}  &nbsp&nbsp&nbsp&nbsp
                            @endforeach
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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
