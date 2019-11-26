@extends('layouts.app')
@section('content')
  <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
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
                                    <th>专业</th>
                                    <th>申请类型</th>
                                    <th>申请时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $key => $val)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$val->user->name}}</td>
                                        <td>{{$val->user->profile->gender_text}}</td>
                                        <td>{{$val->user->gradeUser->major->name}}</td>
                                        <td>{{$val->applicationType->name}}</td>
                                        <td>{{$val->created_at}}</td>
                                        <td>{{$val->status_text}}</td>
                                        <td>
                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'编辑','class'=>'btn-edit-facility','href'=>route('school_manager.students.applications-edit',['id'=>$val->id])], \App\Utils\UI\Button::TYPE_DEFAULT,'edit') }}
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
