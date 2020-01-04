
@extends('layouts.app')
@section('content')
     <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>会议列表</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-md-6 col-sm-6 col-6">
                                <a href="{{ route('teacher.conference.create') }}" class="btn btn-primary">
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
                                    <th>会议主题</th>
                                    <th>会议类型</th>
                                    <th>会议负责人</th>
                                    <th>会议地点</th>
                                    <th>开始时间</th>
                                    <th>结束时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key => $val)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $val->title }}</td>
                                            <td>{{ $val->typeText() }}</td>
                                            <td>{{ $val->user->name }}</td>
                                            <td>{{ $val->room->name }}</td>
                                            <td>{{ $val->from }}</td>
                                            <td>{{ $val->to }}</td>
                                            <td>{{ $val->checkStatus() }}</td>
                                            <td class="text-center">
                                                {{ \App\Utils\UI\Anchor::Print(['text'=>'编辑','class'=>'btn-edit-conference','href'=>route('teacher.conference.edit',['id'=>$val->id])], \App\Utils\UI\Button::TYPE_DEFAULT,'edit') }}
                                                {{ \App\Utils\UI\Anchor::Print(['text'=>'删除','class'=>'btn-delete-conference btn-need-confirm','href'=>route('teacher.conference.delete',['id'=>$val->id])], \App\Utils\UI\Button::TYPE_DANGER,'trash') }}
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
