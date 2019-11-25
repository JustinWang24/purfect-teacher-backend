@extends('layouts.app')
@section('content')
  <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>设置列表</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-md-6 col-sm-6 col-6">
                                <a href="{{ route('school_manager.students.applications-set-add') }}" class="btn btn-primary">
                                    创建 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered
                                    table-hover table-checkable order-column
                                    valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>自定义图标</th>
                                    <th>申请类型</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                @foreach($list as $key => $val)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td></td>
                                        <td>{{$val->name}}</td>
                                        <td>{{$val->getStatusText($val->status)}}</td>
                                        <td>
                                        {{ \App\Utils\UI\Anchor::Print(['text'=>'编辑','class'=>'btn-edit-facility','href'=>route('school_manager.students.applications-set-info',['id'=>$val->id])], \App\Utils\UI\Button::TYPE_DEFAULT,'edit') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
