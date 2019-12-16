@extends('layouts.app')
@section('content')
     <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>学校</th>
                                    <th>手机号</th>
                                    <th>姓名</th>
                                    <th>名称</th>
                                    <th>人数</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                @foreach($list as $key => $val)
                                    <tbody>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $val->school->name }}</td>
                                        <td>{{ $val->user->mobile }}</td>
                                        <td>{{ $val->user->name }}</td>
                                        <td>{{ $val->name }}</td>
                                        <td>{{ $val->member->count() }}</td>
                                        <td>{{ $val->getStatusText() }}</td>
                                        <td class="text-center">
                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'查看','class'=>'btn-edit-forum','href'=>route('teacher.communities.edit',['id'=>$val->id])], \App\Utils\UI\Button::TYPE_DEFAULT,'edit') }}
                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'删除','class'=>'btn-delete-forum btn-need-confirm','href'=>route('teacher.communities.delete',['id'=>$val['id']])], \App\Utils\UI\Button::TYPE_DANGER,'trash') }}
                                        </td>
                                    </tbody>
                                @endforeach
                            </table>
                            {{ $list->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
