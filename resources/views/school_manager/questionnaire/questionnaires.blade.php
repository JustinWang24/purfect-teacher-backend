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
                    <header>{{ session('school.name') }} 问卷调查</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">

                                <a href="{{ route('school_manager.contents.questionnaire.add') }}" class="btn btn-primary " id="btn-create-questionnaire-from">
                                    创建新问卷 <i class="fa fa-plus"></i>
                                </a>


                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>问卷名称</th>
                                    <th style="width: 800px;">简介</th>
                                    <th>选项</th>
                                    <th>状态</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($questionnaires) == 0)
                                    <tr>
                                        <td colspan="5">还没有内容 </td>

                                    </tr>
                                @endif
                                @foreach($questionnaires as $index=>$qiestionnaire)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $qiestionnaire->title }}</td>
                                        <td>{{ $qiestionnaire->detail }}</td>
                                        <td>
                                            总投票数：{{$results[$qiestionnaire->id]['total']}}条<br>
                                            {{ $qiestionnaire->first_question_info }}【{{$results[$qiestionnaire->id]['first']}}条】<br>
                                            {{ $qiestionnaire->second_question_info }}【{{$results[$qiestionnaire->id]['second']}}条】<br>
                                            {{ $qiestionnaire->third_question_info }}【{{$results[$qiestionnaire->id]['third']}}条】<br>

                                        </td>
                                        <td class="text-center">
                                            @if($qiestionnaire->status == 1) 锁定
                                            @elseif($qiestionnaire->status == 2) 激活 @endif
                                        </td>
                                        <td class="text-center">
                                            {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-building','href'=>route('school_manager.contents.questionnaire.edit',['id'=>$qiestionnaire->id])], Button::TYPE_DEFAULT,'edit') }}
                                            {{ Anchor::Print(['text'=>'删除','class'=>'btn-edit-building btn-need-confirm','href'=>route('school_manager.contents.questionnaire.delete',['id'=>$qiestionnaire->id])], Button::TYPE_DEFAULT,'delete') }}
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
