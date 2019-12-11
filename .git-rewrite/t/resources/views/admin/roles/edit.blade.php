<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
/** @var App\Models\Acl\Role $role */
/** @var string[] $targets */
/** @var string[] $actions */
$permissions = $role->getPermissions();
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card box">
                <form action="{{ route('admin.roles.update_permission') }}" method="post">
                @csrf
                <input type="hidden" name="role_slug" value="{{ $role->slug }}">
                <div class="card-head">
                    <header>权限列表</header>
                </div>
                <div class="card-body">
                    @foreach($targets as $key=>$target)
                        <div style="width: 48%;margin:1%;float: left;">
                        @include(
                            'reusable_elements.section.checkbox',
                            ['item'=>$target, 'values'=>$actions, 'idx'=>$key, 'origin'=>$permissions[$target]??null]
                        )
                        </div>
                    @endforeach
                    <?php Button::Print(['id'=>'submit-permissions','text'=>trans('general.submit')],Button::TYPE_PRIMARY); ?>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection