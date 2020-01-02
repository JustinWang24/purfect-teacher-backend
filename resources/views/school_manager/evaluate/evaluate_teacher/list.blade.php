
@extends('layouts.app')
@section('content')
     <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>评教列表</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-md-6 col-sm-6 col-6">
                                <a href="{{ route('school_manager.evaluate-teacher.grade') }}" class="btn btn-primary">
                                    创建 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>教师</th>
                                    <th>学年</th>
                                    <th>学期</th>
                                    <th>分值</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key => $val)
                                        <tr>
                                            <td>{{ $key +1 }}</td>
                                            <td>{{ $val->user->name }}</td>
                                            <td>{{ $val->year }} 学年</td>
                                            <td>{{ $val->typeText() }}</td>
                                            <td>{{ $val->score }}</td>
                                            <td>{{ $val->created_at }}</td>
                                            <td>
                                                {{ \App\Utils\UI\Anchor::Print(['text'=>'查看','class'=>'btn-edit-evaluate','href'=>route('school_manager.evaluate.record-list',['id'=>$val->id])], \App\Utils\UI\Button::TYPE_DEFAULT,'edit') }}

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $list->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
