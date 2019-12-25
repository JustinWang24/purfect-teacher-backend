<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
 <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>姓名</th>
                                    <th>设备名称</th>
                                    <th>类型</th>
                                    <th>描述</th>
                                    <th>创建时间</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->user->name }}</td>
                                        <td>{{ $val->facility->facility_name }}</td>
                                        <td>{{ $val->typeText() }}</td>
                                        <td>{{ $val->desc }}</td>
                                        <td>{{ $val->created_at }}</td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $list->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
