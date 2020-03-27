@extends('layouts.app')
@section('content')
  <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>申请列表</header>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered
                                    table-hover table-checkable order-column
                                    valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>姓名</th>
                                    <th>性别</th>
                                    <th>学院</th>
                                    <th>专业</th>
                                    <th>班级</th>
                                    <th>类型</th>
                                    <th>申请时间</th>
                                    <th>状态</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key => $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->user->name }}</td>
                                        <td>@if($val->user->profile->gender == 1)男@endif
                                            @if($val->user->profile->gender == 2)女@endif</td>

                                        <td>{{ $val->user->gradeUser->institute->name }}</td>
                                        <td>{{ $val->user->gradeUser->major->name }}</td>
                                        <td>{{ $val->user->gradeUser->grade->name }}</td>
                                        <td>{{ $val->flow->name }}</td>
                                        <td>{{ $val->created_at }}</td>
                                        <td>
                                            @if($val->done == \App\Utils\Pipeline\IUserFlow::IN_PROGRESS)审核中@endif
                                            @if($val->done == \App\Utils\Pipeline\IUserFlow::DONE)已通过@endif
                                            @if($val->done == \App\Utils\Pipeline\IUserFlow::TERMINATED)被拒绝@endif
                                        </td>
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
