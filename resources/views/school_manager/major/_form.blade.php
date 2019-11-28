<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
?>
@csrf
<input type="hidden" id="department-id-input" name="major[department_id]" value="{{ $department->id }}">
<input type="hidden" id="school-id-input" name="major[school_id]" value="{{ session('school.id') }}">

<div class="row">
    <div class="col-3">
        <div class="form-group">
            <label for="major-name-input">专业名称</label>
            <input required type="text" class="form-control" id="major-name-input" value="{{ $major->name }}" placeholder="专业名称" name="major[name]">
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label for="major-code-input">专业代码</label>
            <input required type="text" class="form-control" id="major-code-input" value="{{$major['category_code'] ?? old('category_code')}}" placeholder="专业代码" name="major[category_code]">
        </div>
    </div>

    <div class="col-3">
        <div class="form-group">
            <label for="major-period-input">学制</label>
            <input required type="number" class="form-control" id="major-period-input" value="{{$major['period'] ?? old('period')}}" placeholder="学制" name="major[period]" min="1" max="10">
        </div>
    </div>

    <div class="col-3">
        <div class="form-group">
            <label for="major-type-input">类型</label>
            <select  name="major[type]" id="major-type-select" class="form-control">
                @foreach($all_type as $key => $val)
                     <option value="{{$key}}"
                             @if(isset($major['type']))
                                @if($key == $major['type']) selected @endif
                            @endif>
                    {{$val}}
                     </option>
                @endforeach
            </select>
        </div>
    </div>

</div>


<div class="form-group">
    <label for="major-desc">简介</label>
    <textarea required class="form-control" name="major[description]" id="major-desc-input" cols="30" rows="10" placeholder="专业简介">{{ $major->description ?? old('description') }}</textarea>
</div>

<div class="form-group">
    <label for="major-desc">专业备注</label>
    <textarea name="major[notes]" id="major-notes-input" class="form-control" cols="30" rows="10" placeholder="专业备注">{{$major->notes ?? old('notes')}}</textarea>
</div>


<div class="form-group">
    <label for="major-desc">专业优势</label>
    <textarea name="major[advantage_introduction]" id="major-ntroduction-input" class="form-control" cols="30" rows="10" placeholder="专业优势">{{$major->advantage_introduction ?? old('advantage_introduction')}}</textarea>
</div>

<div class="form-group">
    <label for="major-desc">毕业前景</label>
    <textarea name="major[future]" id="major-future-input" class="form-control" cols="30" rows="10" placeholder="毕业前景">{{$major->future ?? old('future')}}</textarea>
</div>


<div class="form-group">
    <label for="major-desc">资助政策</label>
    <textarea name="major[funding_policy]" id="major-policy-input" class="form-control" cols="30" rows="10" placeholder="资助政策">{{$major->funding_policy ?? old('funding_policy')}}</textarea>
</div>
<?php
Button::Print(['id'=>'btn-save-major','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
?>&nbsp;
<?php
Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
?>
