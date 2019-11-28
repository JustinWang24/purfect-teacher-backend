@php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
@endphp

@extends('layouts.app')
@section('content')
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="card">
            <div class="card-head">
                <header>列表</header>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="row table-padding">
                        <div class="col-md-6 col-sm-6 col-6">
                            <a href="{{ route('school_manager.notice.add') }}" class="btn btn-primary">
                                创建 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                            <tr>
                                <th>序号</th>
                                <th>标题</th>
                                <th>类型</th>
                                <th>操作人</th>
                                <th>创建时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $val)
                                <tr>
                                    <td>{{ $val->id }}</td>
                                    <td>{{ $val->title }}</td>
                                    <td>{{ $val->getTypeText() }}</td>
                                    <td>{{ $val->user->name }}</td>
                                    <td>{{ _printDate($val->created_at) }}</td>
                                    <td>
                                        @if($val['status'] == 1)
                                        <span class="label label-sm label-success"> 已发布 </span>
                                            @else
                                        <span class="label label-sm label-danger"> 未发布 </span>
                                        @endif
                                    </td>
                                     <td class="text-center">
                                        {{ Anchor::Print(['text'=>'查看','class'=>'btn-edit-building','href'=>route('school_manager.notice.details',['id'=>$val->id])], Button::TYPE_DEFAULT,'edit') }}
                                        {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-building','href'=>route('school_manager.notice.edit',['id'=>$val->id])], Button::TYPE_DEFAULT,'edit') }}
                                     </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
