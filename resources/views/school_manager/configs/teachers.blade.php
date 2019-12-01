@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
@endphp
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header>{{ session('school.name') }}: 教职工工作考核项目</header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('school_manger.configs.performance-teacher-save') }}" method="post"  id="edit-school-config-form">
                                @csrf
                                <input type="hidden" name="item[id]" value="{{ $item->id??null }}">
                                <input type="hidden" name="item[school_id]" value="{{ session('school.id') }}">
                                <div class="form-group">
                                    <label>考核项目名称</label>
                                        <input required type="text" class="form-control" value="{{ $item->name??null }}"
                                               placeholder="必填: 考核项名称" name="item[name]">
                                </div>
                                <div class="form-group">
                                    <label>考核标准</label>
                                    <textarea required class="form-control" placeholder="必填: 考核标准详情" name="item[description]">{{ $item->description??null }}</textarea>
                                </div>
                                <hr>
                                <?php
                                Button::Print(['id'=>'btn-save-school-config','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                ?>&nbsp;
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <span class="pull-left pt-2">教职工工作考核项目列表</span>
                        <a href="{{ route('school_manager.school.teachers') }}" class="btn btn-primary pull-right">
                            全校教职工列表
                        </a>
                    </header>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>考核项目</th>
                                    <th>考核标准</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($configs as $index=>$config)
                                    <tr>
                                        <td>{{ $config->name }}</td>
                                        <td>{{ $config->description }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-config','href'=>route('school_manger.configs.performance-teacher',['item'=>$config->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-delete-config btn-need-confirm','href'=>route('school_manger.configs.performance-teacher-delete',['item'=>$config->id])], Button::TYPE_DANGER,'trash') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
