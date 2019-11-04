
@extends('layouts.app')
@section('content')
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>监控列表</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="row table-padding">
                            <div class="col-md-6 col-sm-6 col-6">
                                <a href="{{ route('school_manager.facility.add') }}" class="btn btn-primary">
                                    创建监控 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table
                                    class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>设备编号</th>
                                    <th>设备名称</th>
                                    <th>校区</th>
                                    <th>楼群</th>
                                    <th>教室</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($facility as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val['facility_number'] }}</td>
                                        <td>{{ $val['facility_name'] }}</td>
                                        <td>{{$val['campus']['name']}}</td>
                                        <td>{{ $val['building']['name'] }}</td>
                                        <td>{{$val['room']['name']}}</td>
                                        <td>{{$val['created_at']}}</td>
                                        <td class="text-center">
                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'编辑','class'=>'btn-edit-facility','href'=>route('school_manager.facility.edit',['id'=>$val['id']])], \App\Utils\UI\Button::TYPE_DEFAULT,'edit') }}
                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'删除','class'=>'btn-delete-facility btn-need-confirm','href'=>route('school_manager.facility.delete',['id'=>$val['id']])], \App\Utils\UI\Button::TYPE_DANGER,'trash') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $facility->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
