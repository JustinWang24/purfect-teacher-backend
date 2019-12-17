<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在校区 (<a href="{{ route('school_manager.campus.buildings',['uuid'=>$building->campus->id]) }}">{{ $building->campus->name }}</a>) 的 <a href="{{ route('school_manager.building.rooms',['uuid'=>$building->id]) }}">{{ $building->name }}</a> 修改房间信息</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.room.update') }}" method="post"  id="edit-room-form">
                        @csrf
                        <input type="hidden" id="room-id-input" name="room[id]" value="{{ $room->id }}">
                        <input type="hidden" id="room-campus-id" name="room[campus_id]" value="{{ $building->campus->id }}">
                        <input type="hidden" id="room-building-id" name="room[building_id]" value="{{ $building->id }}">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>房间类型</label>
                                    <select class="form-control" id="room-type-input" name="room[type]">
                                        @foreach(\App\Models\Schools\Room::AllTypes() as $type=>$text)
                                            <option value="{{ $type }}"
                                                @if($room->type == $type) selected @endif
                                            >{{ $text }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="room-seats-input">可容纳人数</label>
                                    <div class="input-group mb-3">
                                        <input required type="text" class="form-control" id="room-seats-input" value="{{ $room->seats }}" placeholder="房间最多可容纳人数" name="room[seats]">
                                        <div class="input-group-append">
                                            <span class="input-group-text">座位数/人数</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="building-name-input">房间编号</label>
                            <input required type="text" class="form-control" id="room-name-input" value="{{ $room->name }}" placeholder="房间编号" name="room[name]">
                        </div>
                        <div class="form-group">
                            <label for="room-desc-input">房间名称</label>
                            <input required type="text" class="form-control" id="room-desc-input" value="{{ $room->description }}" placeholder="房间名称" name="room[description]">

                        </div>
                        <?php
                        Button::Print(['id'=>'btn-save-room','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="panel">
                <header class="panel-heading panel-heading-blue">其他已创建的房间: ({{ count($building->rooms) - 1 }}间)</header>
                <div class="panel-body">
                    <div id="existed-rooms-view" class="treeview">
                        <ul class="list-group">
                            @foreach($building->rooms as $theRoom)
                                @if($room->id !== $theRoom->id)
                                <li class="list-group-item">
                                    <a href="{{ route('school_manager.room.edit',['uuid'=>$theRoom->id]) }}">{!! $theRoom->getTypeHtmlText() !!} {{ $theRoom->name }} </a>
                                    <a href="{{ route('school_manager.room.delete',['uuid'=>$theRoom->id]) }}" data-toggle="button" class="btn btn-circle btn-danger btn-sm btn-need-confirm pull-right"><i class="fa fa-trash"></i>
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
