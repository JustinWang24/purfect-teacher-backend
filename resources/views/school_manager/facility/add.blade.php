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
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.facility.add') }}" method="post"  id="add-building-form">
                        @csrf
                        <div class="form-group">
                            <label for="facility-number-input">设备编号</label>
                            <input required type="text" class="form-control" id="facility-number-input" value="" placeholder="设备编号" name="facility[facility_number]">
                        </div>
                        <div class="form-group">
                            <label for="facility-name-input">设备名称</label>
                            <input required type="text" class="form-control" id="facility-name-input" value="" placeholder="设备名称" name="facility[facility_name]">
                        </div>

                        <div class="form-group">
                            <label for="facility-name-input">类型</label>

                            <select required type="select" class="form-control" id="facility-type-select" value="" placeholder="类型" name="facility[type]">
                                <option value="1">监控设备</option>
                                <option value="2">门禁设备</option>
                                <option value="3">班牌设备</option>
                                <option value="4">教室设备</option>
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="facility-campus-select">校区</label>
                            <select required type="text" class="form-control" id="facility-campus-select" value="" placeholder="校区" name="facility[campus_id]">
                               @foreach($campus as $key => $val)
                                <option value="{{$val['id']}}">{{$val['name']}}</option>
                               @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="facility-building-select">建筑</label>
                            <select required type="text" class="form-control" id="facility-building-select" value="" placeholder="建筑" name="facility[building_id]">
                                <option value="1">1</option>
                                <option value="1">1</option>
                                <option value="1">1</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="facility-room-select">教室</label>
                            <select required type="text" class="form-control" id="facility-room-select" value="" placeholder="教室" name="facility[room_id]">
                                <option value="1">1</option>
                                <option value="1">1</option>
                                <option value="1">1</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="facility-addr-input">详细地址</label>
                            <input  type="text" class="form-control" id="facility-addr-input" value="" placeholder="详细地址" name="facility[detail_addr]">
                        </div>


                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>'','class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
