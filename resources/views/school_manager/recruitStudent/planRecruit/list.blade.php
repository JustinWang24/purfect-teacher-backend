
@extends('layouts.app')
@section('content')
      <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>预招列表</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
{{--                            <div class="col-md-6 col-sm-6 col-6">--}}
{{--                                <a href="{{ route('admin.schools.add') }}" class="btn btn-primary">--}}
{{--                                    创建新学校 <i class="fa fa-plus"></i>--}}
{{--                                </a>--}}
{{--                            </div>--}}
                        </div>
                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>专业名称</th>
                                    <th>计划招收</th>
                                    <th>已招手</th>
                                    <th>学费</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($major as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val['name'] }}</td>
                                        <td>{{ $val['seats'] }}</td>
                                        <td></td>
                                        <td>{{ $val['fee'] }}</td>
                                        <td></td>
{{--                                        <td>{{ $school->max_employees_number > 0 ? $school->max_employees_number : '不限' }}</td>--}}
{{--                                        <td>{{ $school->last_updated_by ? $school->lastUpdatedBy->mobile : '超级管理员' }} {{ $school->updated_at }}</td>--}}
                                        <td class="text-center">
{{--                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'编辑','href'=>route('admin.schools.edit',['uuid'=>$school->uuid])], \App\Utils\UI\Button::TYPE_DEFAULT,'edit') }}--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $major->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
