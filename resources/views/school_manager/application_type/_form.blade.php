
@csrf
<div class="col-3">
    <div class="form-group">
        <label for="type-name-input">申请类型名称</label>
        <input required type="text" class="form-control" id="type-name-input" value="{{ $type->name ?? old('name') }}" placeholder="必填: 申请类型名称" name="type[name]">
    </div>
</div>


