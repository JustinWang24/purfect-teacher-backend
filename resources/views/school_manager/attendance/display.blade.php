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
                    <header>{{ session('school.name') }} - 《{{$task->title}}》任务的本周安排</header>
                    <?php
                    Anchor::Print(['text'=>trans('general.return'),'href'=>route('school_manager.attendance.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                    ?>
                </div>

                <div class="card-body">
                    <div class="row">
                         <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>周一</th>
                                    <th>周二</th>
                                    <th>周三</th>
                                    <th>周四</th>
                                    <th>周五</th>
                                    <th>周六</th>
                                    <th>周日</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($slots as $index=>$slot)
                                    <tr>
                                        <td style="width: 200px;">{{ $slot->title }}({{$slot->start_time}}到{{$slot->end_time}})</td>
                                        @for($i=1;$i<=7;$i++)
                                            @if((strtotime($time[0])+($i-1)*86400)>=strtotime($task->start_time) && (strtotime($time[0])+($i-1)*86400)<=strtotime($task->end_time))
                                                <td>
                                                    @if(!empty($data[$i][$slot->id]))
                                                        @foreach($data[$i][$slot->id] as $user)
                                                            {{$user['userName']}}{{$user['department']}}{{$user['major']}}|
                                                        @endforeach
                                                    @endif
                                                </td>
                                            @else
                                                <td></td>
                                            @endif

                                        @endfor
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
