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
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manager.attendance.update') }}" method="post"  id="add-attendance-form">
                        @csrf
                        <input type="hidden" name="task[id]" value="{{ $task->id }}">
                        <div class="form-group">
                            <label for="attendance-title-input">任务名称</label>
                            <input required type="text" class="form-control" id="attendance-title-input" value="{{ $task->title }}" placeholder="任务名称" name="task[title]">
                        </div>
                        <div class="form-group">
                            <label for="task-detail">任务具体内容</label>
                            <textarea required class="form-control" name="task[content]" id="task-desc-input" cols="30" rows="10" placeholder="任务内容">{{ $task->content }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="attendance-option-input">开始时间</label>
                            <input required type="date" class="form-control" id="attendance-option-input" value="{{ $task->start_time }}" placeholder="开始时间" name="task[start_time]">
                        </div>
                        <div class="form-group">
                            <label for="attendance-option-input">结束时间</label>
                            <input required type="date" class="form-control" id="attendance-option-input" value="{{ $task->end_time }}" placeholder="结束时间" name="task[end_time]">
                        </div>
                        @if($taskSlotNum==0)
                        <div class="form-group">
                            <label for="questionnaire-option-input">是否使用默认周期</label>
                            <input type="radio" value="1" name="task[status]" @if($task->status == 1 || empty($task->status)) checked @endif> 是&nbsp&nbsp&nbsp&nbsp
                            <input type="radio" value="2" name="task[status]" @if($task->status == 2) checked @endif> 否

                        </div>
                        @endif
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
