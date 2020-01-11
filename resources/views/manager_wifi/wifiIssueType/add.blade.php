<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-10 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('manager_wifi.wifiIssueType.add',request()->only('typeid','type_pid','purpose')) }}" method="post"  id="add-building-form">
                        @csrf
						@if( Request::get('type_pid') == null  || Request::get('type_pid') == 0 )
						<div class="form-group">
                            <!--类型(1:无线,2:有线)-->
                            <label for="school-name-input">模式</label>
                            <select class="form-control" name="infos[purpose]"  required>
                                <option value="">请选择</option>
                                <option value="1">APP报修类型</option>
                                <option value="2">后台报修类型</option>
                            </select>
                        </div>
						@else
							<input required type="hidden" class="form-control" value="{{ Request::get('purpose') }}" name="infos[purpose]">
						@endif
                        <div class="form-group">
                            <label for="building-name-input">名称</label>
                            <input required type="text" class="form-control" id="building-name-input" value="" placeholder="例如：无法上网" name="infos[type_name]" size="20">
                        </div>
                        <?php
                        Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                         $returnUrl = route('manager_wifi.wifiIssueType.list',request()->only('type_pid'));
                         Anchor::Print(['text'=>trans('general.return'),'href'=>$returnUrl,'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection