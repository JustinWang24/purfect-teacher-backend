<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} - {{ $parent->name }}</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ url('school_manager/school/view') }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="{{ route('school_manager.building.add',['uuid'=>$parent->id,'type'=>$type]) }}" class="btn btn-primary pull-right" id="btn-create-building-from-campus">
                                    添加新楼 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>所在校区</th>
                                    <th>建筑物名称</th>
                                    <th style="width: 300px;">简介</th>
                                    <th>房间数</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($buildings as $index=>$building)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>
                                            {{ $parent->name }}
                                        </td>
                                        <td>{{ $building->name }}</td>
                                        <td>{{ $building->description }}</td>
                                        <td class="text-center">
                                            <a class="anchor-rooms-counter" href="{{ route('school_manager.building.rooms',['uuid'=>$building->id,'by'=>'building']) }}">{{ count($building->rooms) }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-building','href'=>route('school_manager.building.edit',['uuid'=>$building->id])], Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
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
