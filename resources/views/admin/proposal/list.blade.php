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
                <header>意见反馈</header>
            </div>

            <div class="card-body">
                <div class="row">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>用户</th>
                            <th>反馈内容</th>
                            <th>反馈时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                            $index = 1;
                            @endphp
                            @foreach($data as $key => $val)
                            <tr>
                                <td>{{ $index }}</td>
                                <td class="center">{{ $val->user->name }}</td>
                                <td class="center">@php echo mb_substr($val->content, 0, 10) @endphp</td>
                                <td>{{$val->created_at}}</td>
                                <td class="text-center">
                                    {{ Anchor::Print(['text'=>'详情','href'=>route('admin.proposal.info',['id' => $val->id])], Button::TYPE_PRIMARY,'edit') }}
                                </td>
                            </tr>
                            @php
                                $index++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                     {{ $data->appends(Request::all())->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
