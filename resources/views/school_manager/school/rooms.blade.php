<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>{{ session('school.name') }} - {{ $building->name ?? null }}</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                            @if(isset($building)))
                                <a href="{{ url()->previous() }}" class="btn btn-default">
                                    <i class="fa fa-arrow-circle-left"></i> 返回
                                </a>&nbsp;
                                <a href="{{ route('school_manager.room.add',['uuid'=>$building->id]) }}" class="btn btn-primary pull-right" id="btn-create-room-from-building">
                                    添加新房间 <i class="fa fa-plus"></i>
                                </a>
                            @endif
                            @include('school_manager.school.reusable.nav',['highlight'=>'room'])
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>房间名称</th>
                                    <th>类型</th>
                                    <th style="width: 300px;">房间简介</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rooms as $index=>$room)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $room->name }}</td>
                                        <td>{{ $room->getTypeText() }}</td>
                                        <td>{{ $room->description }}</td>
                                        <td class="text-center">
                                            <a target="_blank" href="{{ route('school_manager.room.view.timetable',['uuid'=>$room->id]) }}" id="" class="btn btn-round btn-default btn-room-timetable">
                                                <i class="fa fa-calendar"></i>排课记录
                                            </a>
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-room','href'=>route('school_manager.room.edit',['uuid'=>$room->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-delete-room btn-need-confirm','href'=>route('school_manager.room.delete',['uuid'=>$room->id])], Button::TYPE_DANGER,'trash') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if(!isset($building)))
{{ $rooms->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection