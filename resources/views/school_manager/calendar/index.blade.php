@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-5 col-xl-5">
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
                                <input required type="text" class="form-control" id="" name="event[event_time]" placeholder="事件的执行时间" value="">
                            </div>
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-facility','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-5 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }} 校历</header>
                </div>
                <div class="card-body">
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
