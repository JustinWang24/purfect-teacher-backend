
@extends('layouts.app')
@section('content')
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="card">
            <div class="card-head">
                <header>评分详情列表</header>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table
                                class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>学生</th>
                                <th>签到状态</th>
                                <th>分数</th>
                                <th>备注</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $key => $val)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $val->user->name }}</td>
                                    <td>{{ $val->getMold() }}</td>
                                    <td>{{ $val->score }}</td>
                                    <td>{{ $val->remark }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $list->appends(['attendance_id'=>$val->attendance_id])->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
