
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header class="full-width">
                        <a href="{{ route('teacher.community.dynamic.type.add') }}" class="btn btn-primary pull-right">
                            添加分类 <i class="fa fa-plus"></i>
                        </a>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>学校</th>
                                    <th>名称</th>
                                    <th>属于</th>
                                    <th>添加时间</th>
                                </tr>
                                </thead>
                                @foreach($list as $key => $val)
                                    <tbody>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->school->name }}</td>
                                        <td>{{ $val->title }}</td>
                                        <td>{{ $val->getTypeName() }}</td>
                                        <td>{{ $val->created_at }}</td>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
