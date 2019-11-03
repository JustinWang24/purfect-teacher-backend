<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
      <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>咨询列表</header>
                </div>

                <div class="card-body">
                     <div class="row table-padding">
                            <div class="col-12">
                                <a href="{{ route('school_manager.consult.add') }}" class="btn btn-primary" id="btn-create-campus-from-school">
                                    添加 <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                    <tr>
                                        <th>序号</th>
                                        <th>问题</th>
                                        <th>回答</th>
                                        <th>添加时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($consult as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val['question'] }}</td>
                                        <td>{{ $val['answer'] }}</td>
                                        <td>{{ $val['created_at'] }}</td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','href'=>route('school_manager.consult.edit',['id'=>$val['id']])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-delete-room btn-need-confirm','href'=>route('school_manager.consult.delete',['id'=>$val['id']])], Button::TYPE_DANGER,'trash') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $consult->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
