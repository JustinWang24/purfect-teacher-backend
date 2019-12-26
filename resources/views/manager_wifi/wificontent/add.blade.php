<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
	    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-sm-10 col-md-12 col-xl-12">
            <div class="card-box">
                <div class="card-head">
                    <header>添加</header>
                </div>
                <div class="card-body">
                    <form action="{{ route('manager_wifi.wifiContent.add') }}" method="post"  id="add-building-form">
                        @csrf
                        <div class="form-group">
                            <label for="school-name-input">学校</label>
                            <select id="cityid" class="form-control" name="infos[school_id]"  required></select>
                        </div>
                        <div class="form-group">
                            <label for="school-name-input">校区</label>
                            <select id="countryid" class="form-control" name="infos[campus_id]"  required></select>
                        </div>
                        <div class="form-group">
                            <label for="school-name-input">类型</label>
                            <select class="form-control" name="infos[typeid]"  required>
                                <option value="">请选择</option>
                                @foreach($wifiContentsTypeArr as $key=>$val)
                                    <option value="{{$key}}" {{ (old('infos.typeid') == $key ? "selected":"") }}>{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="building-name-input">内容</label>
							<textarea required class="form-control" name="infos[content]" id="questionnaire-desc-input" cols="30" rows="10" placeholder="">{{ old('infos.content') }}</textarea>
                        </div>
                        <?php
                            Button::Print(['id'=>'btn-create-building','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                        ?>&nbsp;
                        <?php
                            Anchor::Print(['text'=>trans('general.return'),'href'=>route('manager_wifi.wifiContent.list'),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
window.onload=function() {
    showLocation({{ old('infos.school_id')?:0 }},{{ old('infos.campus_id')?:0 }});
}
</script>
@endsection