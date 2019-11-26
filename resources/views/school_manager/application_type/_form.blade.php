
@csrf
<div class="col-3">
    <div class="form-group">
        <label for="major-name-input">设备申请类型</label>
        <input required type="text" class="form-control" id="type-name-input" value="{{ $type->name ?? old('name') }}" placeholder="设备申请类型" name="type[name]">
    </div>
</div>


