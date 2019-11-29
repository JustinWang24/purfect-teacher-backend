@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp

@extends('layouts.app')
@section('content')
    <div class="row" id="school-calendar-app">
        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="card">
                <div class="card-head">
                    <header>
                        {{ session('school.name') }}  添加校历事件
                    </header>
                </div>
                <div class="card-body">
                    <form action="{{ route('school_manger.school.calendar.save') }}" method="post">
                    @csrf
                        <input type="hidden" name="event[id]" value="">
                        <div class="table-responsive">
                            <div class="form-group">
                                <label for="calendar-tag-input">事件标签</label>
                                <input required type="text" class="form-control" id="" name="event[tag]" placeholder="比如:迎新生,校庆,开学" value="">
                            </div>
                            <div class="form-group">
                                <label for="calendar-content-input">事件内容</label>
                                <input required type="text" class="form-control" id="" name="event[content]" placeholder="事件的具体内容" value="">
                            </div>
                            <div class="form-group">
                                <label for="calendar-time-input">事件时间</label>
                                <input required type="date" class="form-control" id="" name="event[event_time]" placeholder="事件的执行时间" value="">
                            </div>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 校历</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <el-calendar v-on:input="dateClicked">
                            <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
                            <template
                                    slot="dateCell"
                                    slot-scope="{date, data}">
                                <p :class="data.isSelected ? 'is-selected' : ''">
                                    @{{ data.day.split('-').slice(1).join('-') }} @{{ data.isSelected ? '✔️' : ''}}
                                </p>
                            </template>
                        </el-calendar>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
