<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="">
        <form action="{{ route('school_manager.teachers.manage-performance-save') }}" method="post" class="row">
            @csrf
            <input type="hidden" name="performance[user_id]" value="{{ $teacher->id }}">
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">{{ $teacher->name }} 年终考评</span>
                    </header>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>评定年度</label>
                        <input required type="text" class="form-control" name="performance[year]" placeholder="必填: 评审的年度" value="{{ date('Y') - 1 }}" />
                    </div>
                    <div class="form-group">
                        <label>今年职称</label>
                        <input required type="text" class="form-control" name="performance[title]" placeholder="必填: 职称" />
                    </div>
                    <div class="form-group">
                        <label>评定部门</label>
                        <input required type="text" class="form-control" name="performance[organisation_name]" placeholder="必填: 服务的部门" />
                    </div>
                    <div class="form-group">
                        <label>考评综合评定</label>
                        <textarea required class="form-control" name="performance[comments]"></textarea>
                    </div>
                    <div class="form-group">
                        <label>考评综合结果</label>
                        <select class="form-control" name="performance[result]">
                            @foreach(\App\Models\Teachers\Performance\TeacherPerformance::Results() as $v=>$t)
                                <option value="{{ $v }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <?php
                    Button::Print(['id'=>'btn-create-campus','text'=>'提交'], Button::TYPE_PRIMARY);
                    ?>&nbsp;
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-xl-6">
            @foreach($configs as $key => $config)
                <input type="hidden" name="items[{{$config->id}}][teacher_performance_config_id]" value="{{ $config->id }}">
                <input type="hidden" name="items[{{$config->id}}][school_id]" value="{{ session('school.id') }}">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">考察项目: {{ $config->name }}</span>
                    </header>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>考评结果</label>
                        <select class="form-control" name="items[{{$config->id}}][result]">
                            @foreach(\App\Models\Teachers\Performance\TeacherPerformance::Results() as $v=>$t)
                            <option value="{{ $v }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>考评详情</label>
                        <textarea required class="form-control" name="items[{{$config->id}}][comments]"></textarea>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        </form>
    </div>
@endsection
