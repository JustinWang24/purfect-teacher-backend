<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
 <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12" id="application-type-app">
            <div class="card-box">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.students.applications-set-save') }}" method="post"  id="add-application-type-form">
                        @include('school_manager.application_type._form')
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
