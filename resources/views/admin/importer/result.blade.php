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
                    <header>结果展示</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>错误原因</th>
                                    <th>原始数据</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($messages) == 0)
                                    <tr>
                                        <td colspan="4">还没有内容 </td>

                                    </tr>
                                @endif
                                @foreach($messages as $index=>$info)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ json_encode(json_decode($info->result,1),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</td>
                                        <td>{{ json_encode(json_decode($info->source,1),JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</td>
                                        <td class="text-center">
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
