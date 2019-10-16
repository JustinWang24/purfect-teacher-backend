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
                <header>系统角色</header>
            </div>

            <div class="card-body">
                <div class="row">
                <div class="table-scrollable">
                    <table
                            class="table table-striped table-bordered table-hover table-checkable order-column valign-middle"
                            id="roles-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>角色名</th>
                            <th>权限列表</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                            $index = 1;
                            @endphp
                            @foreach($roles as $slug=>$roleName)
                            <tr>
                                <td>{{ $index }}</td>
                                <td>{{ $roleName }}</td>
                                <td></td>
                                <td class="text-center">
                                    {{ Anchor::Print(['text'=>'编辑','href'=>route('admin.roles.edit'),'params'=>['slug'=>$slug]], Button::TYPE_PRIMARY,'edit') }}
                                </td>
                            </tr>
                            @php
                                $index++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection