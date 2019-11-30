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
                    <header>在学校 ({{ session('school.name') }}) 编辑值周表</header>
                    <?php
                    Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.attendance.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="card card-box">
                            <div class="card-head">
                                <header>周期管理</header>
                            </div>
                            <div class="card-body " id="bar-parent">
                                <form action="{{ route('school_manager.attendance.timeslots.update') }}" method="post"  id="add-attendance-form">
                                    @csrf
                                    <input type="hidden" name="task[id]" value="{{ $task->id }}">
                                    <input type="hidden" name="timeslots[id]" value="{{ $timeSlots[0]->id }}">
                                    <div class="form-group">
                                        <label for="timeslot-option-input">名称</label>
                                        <input required type="text" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[0]->title }}" placeholder="开始时间" name="timeslots[title]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">开始时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[0]->start_time }}" placeholder="开始时间" name="timeslots[start_time]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">结束时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[0]->end_time }}" placeholder="结束时间" name="timeslots[end_time]">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="card card-box">
                            <div class="card-head">
                                <header>周期管理</header>
                            </div>
                            <div class="card-body " id="bar-parent">
                                <form action="{{ route('school_manager.attendance.timeslots.update') }}" method="post"  id="add-attendance-form">
                                    @csrf
                                    <input type="hidden" name="task[id]" value="{{ $task->id }}">
                                    <input type="hidden" name="timeslots[id]" value="{{ $timeSlots[1]->id }}">
                                    <div class="form-group">
                                        <label for="timeslot-option-input">名称</label>
                                        <input required type="text" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[1]->title }}" placeholder="开始时间" name="timeslots[title]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">开始时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[1]->start_time }}" placeholder="开始时间" name="timeslots[start_time]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">结束时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[1]->end_time }}" placeholder="结束时间" name="timeslots[end_time]">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="card card-box">
                            <div class="card-head">
                                <header>周期管理</header>
                            </div>
                            <div class="card-body " id="bar-parent">
                                <form action="{{ route('school_manager.attendance.timeslots.update') }}" method="post"  id="add-attendance-form">
                                    @csrf
                                    <input type="hidden" name="task[id]" value="{{ $task->id }}">
                                    <input type="hidden" name="timeslots[id]" value="{{ $timeSlots[2]->id }}">
                                    <div class="form-group">
                                        <label for="timeslot-option-input">名称</label>
                                        <input required type="text" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[2]->title }}" placeholder="开始时间" name="timeslots[title]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">开始时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[2]->start_time }}" placeholder="开始时间" name="timeslots[start_time]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">结束时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[2]->end_time }}" placeholder="结束时间" name="timeslots[end_time]">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="card card-box">
                            <div class="card-head">
                                <header>周期管理</header>
                            </div>
                            <div class="card-body " id="bar-parent">
                                <form action="{{ route('school_manager.attendance.timeslots.update') }}" method="post"  id="add-attendance-form">
                                    @csrf
                                    <input type="hidden" name="task[id]" value="{{ $task->id }}">
                                    <input type="hidden" name="timeslots[id]" value="{{ $timeSlots[3]->id }}">
                                    <div class="form-group">
                                        <label for="timeslot-option-input">名称</label>
                                        <input required type="text" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[3]->title }}" placeholder="开始时间" name="timeslots[title]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">开始时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[3]->start_time }}" placeholder="开始时间" name="timeslots[start_time]">
                                    </div>
                                    <div class="form-group">
                                        <label for="timeslot-option-input">结束时间</label>
                                        <input required type="time" class="form-control" id="timeslot-option-input" value="{{ $timeSlots[3]->end_time }}" placeholder="结束时间" name="timeslots[end_time]">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection





