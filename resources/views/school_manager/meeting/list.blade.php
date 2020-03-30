
@extends('layouts.app')
@section('content')
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="card">
            <div class="card-head">
                <header>会议列表</header>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="table-responsive">
                        <table
                                class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>申请人</th>
                                <th>会议主题</th>
                                <th>会议负责人</th>
                                <th>会议地点</th>
                                <th>开始时间</th>
                                <th>结束时间</th>
                                <th>创建时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $key => $val)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $val->user->name }}</td>
                                    <td>{{ $val->meet_title }}</td>
                                    <td>{{ $val->approve->name }}</td>
                                    @if($val->type == 0)
                                        <td>{{ $val->room_text }}</td>
                                        @else
                                    <td>{{  $val->room->building->name.$val->room->description}}</td>
                                    @endif
                                    <td>{{ $val->meet_start }}</td>
                                    <td>{{ $val->meet_end }}</td>
                                    <td>{{ $val->created_at }}</td>
                                    @if($val->status == 0)
                                        <td><button type="button" class="btn btn-circle btn-info">审核中</button></td>
                                    @elseif($val->status == 1)
                                        <td><button type="button" class="btn btn-circle btn-danger">已拒绝</button></td>
                                    @else
                                        @if($now < $val->meet_start)
                                        <td><button type="button" class="btn btn-circle btn-default">待开始</button></td>
                                            @elseif($now >$val->meet_start &&   $now < $val->meet_end)
                                            <td><button type="button" class="btn btn-circle btn-primary">进行中</button></td>
                                            @else
                                            <td><button type="button" class="btn btn-circle btn-default">已结束</button></td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        {{ $list->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
