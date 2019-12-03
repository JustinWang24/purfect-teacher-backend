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
                    <header>在学校 ({{ session('school.name') }}) 创建新值周表</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.attendance.update') }}" method="post"  id="add-attendance-form">
                        @csrf
                        <div class="form-group">
                            <label for="attendance-title-input">任务名称</label>
                            <input required type="text" class="form-control" id="attendance-title-input" value="{{ old('task.title') }}" placeholder="任务名称" name="task[title]">
                        </div>
                        <div class="form-group">
                            <label for="task-detail">任务具体内容</label>
                            <textarea required class="form-control" name="task[content]" id="task-desc-input" cols="30" rows="10" placeholder="任务内容">{{ old('task.content') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="attendance-option-input">开始时间</label>
                            <input required type="date" class="form-control" style="width:200px;" id="attendance-option-input" value="{{ old('task.start_time') }}" placeholder="开始时间" name="task[start_time]">
                        </div>
                        <div class="form-group">
                            <label for="attendance-option-input">结束时间</label>
                            <input required type="date" class="form-control" style="width:200px;" id="attendance-option-input" value="{{ old('task.end_time') }}" placeholder="结束时间" name="task[end_time]">
                        </div>

                        <?php
                        Button::Print(['id'=>'btn-create-attendance','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.attendance.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
