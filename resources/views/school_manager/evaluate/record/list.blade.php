
@extends('layouts.app')
@section('content')
     <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>学生评教列表</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">

                        </div>
                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>姓名</th>
                                    <th>性别</th>
                                    <th>班级</th>
                                    <th>分数</th>
                                    <th>创建时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key => $val)
                                    <tr>
                                        <td>{{ $key +1 }}</td>
                                        <td>{{ $val->user->name }}</td>
                                        <td>{{ $val->user->profile->getGenderTextAttribute()}}</td>
                                        <td>{{ $val->grade->name }}</td>
                                        <td>{{ $val->score }}</td>
                                        <td>{{ $val->created_at }}</td>
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
