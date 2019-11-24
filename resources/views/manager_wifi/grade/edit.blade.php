<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
$currentYear = intval(date('Y'));
$years = range(2014, $currentYear+1);
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>在学校 ({{ session('school.name') }}) - 编辑专业: {{ $parent->name }}</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form action="{{ route('school_manager.grade.update') }}" method="post" id="edit-grade-form">
                        @csrf
                        <input type="hidden" id="grade-id-input" name="grade[id]" value="{{ $grade->id }}">
                        <input type="hidden" id="grade-major-id-input" name="grade[major_id]" value="{{ $parent->id }}">
                        <div class="form-group">
                            <label>入学年份</label>
                            <select class="form-control" id="grade-year-input" name="grade[year]">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year===$grade->year ? 'selected' : null }}>{{ $year }}年</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="major-name-input">班级名称</label>
                            <input required type="text" class="form-control" id="grade-name-input" value="{{ $grade->name }}" placeholder="班级名称" name="grade[name]">
                        </div>
                        <div class="form-group">
                            <label for="grade-desc-input">简介</label>
                            <textarea class="form-control" name="grade[description]" id="grade-desc-input" cols="30" rows="10" placeholder="班级简介">{{ $grade->description }}</textarea>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-save-grade','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                        Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection