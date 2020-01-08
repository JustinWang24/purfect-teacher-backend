@extends('layouts.app')
@section('content')
    <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="card">
            <div class="card-head">
                <header>资料列表</header>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="row table-padding">
                        <div class="col-md-6 col-sm-6 col-6">
                            <a href="{{ route('school_manager.teachers.add.qualification',['uuid'=> $uuid])}} " class="btn btn-primary">
                                创建 <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>标题</th>
                                <th>资料</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                                <tbody>
                                    @foreach($data as $key => $val)
                                        <tr>
                                            <td>{{ $key +1 }}</td>
                                            <td>{{ $val->title }}</td>
                                            <td class="center"><img src="{{ $val->path }}" alt="" height="80px" width="80px"></td>
                                            <td>{{ $val->created_at }}</td>
                                            <td class="text-center">
                                            {{ \App\Utils\UI\Anchor::Print(['text'=>'删除','class'=>'btn-delete-evaluate btn-need-confirm','href'=>route('school_manager.teachers.del.qualification',['id'=>$val->id, 'uuid' => $uuid])], \App\Utils\UI\Button::TYPE_DANGER,'trash') }}
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
    </div>
@endsection
