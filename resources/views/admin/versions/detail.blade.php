<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header>{{ $pageTitle }}</header>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="table-padding col-12">
                                <a href="{{ route('admin.versions.list') }}" class="btn btn-primary pull-right">
                                    返回
                                </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle " style="text-align: center">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>终端</th>
                                    <th>类型</th>
                                    <th>更新</th>
                                    <th>版本号</th>
                                    <th>版本号名称</th>
                                    <th width="25%">更新内容</th>
                                    <th>下载地址</th>
                                    <th>弹框失效时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($versions) == 0)
                                    <tr>
                                        <td colspan="8">还没有内容 </td>

                                    </tr>
                                @endif
                                @foreach($versions as $index=>$version)
                                    <tr>
                                        <td>{{ $version->sid }}</td>
                                        <td>
                                            @if($version->user_apptype ==1) 校园版 @endif
                                            @if($version->user_apptype ==2) 教师版 @endif
                                        </td>
                                        <td>{{ $typeidArr[$version->typeid] }}</td>
                                        <td>{{ $isupdateArr[$version->isupdate] }}</td>
                                        <td>{{ $version->version_id }}</td>
                                        <td>{{ $version->version_name }}</td>
                                        <td>{{ $version->version_content }}</td>
                                        <td>
                                            @if($version->typeid == 1)
                                                <a href="{{ route('api.version.download',['sid'=>$version->sid]) }}"  target="_blank" >{{ $version->created_at }}
                                                    @if($version->typeid == 1) .apk @endif
                                                </a>
                                            @else
                                                <a href="{{ $version->version_downurl }}"  target="_blank" >{{ $version->created_at }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($version->vserion_invalidtime > 0) {{ date('Y-m-d H:i',$version->vserion_invalidtime) }} @else if 无 @endif
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
