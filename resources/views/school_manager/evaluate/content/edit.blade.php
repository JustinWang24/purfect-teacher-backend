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
                    <header>修改</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.content.edit') }}" method="post"  id="edit-evaluate-form">
                        @csrf
                        <input type="hidden" id="evaluate-id" name="evaluate[id]" value="{{$evaluate['id']}}">

                        @include('school_manager.evaluate.content._form')

                        <?php
                        Button::Print(['id'=>'btn-edit-evaluate','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
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
