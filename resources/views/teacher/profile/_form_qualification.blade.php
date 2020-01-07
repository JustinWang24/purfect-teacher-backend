@csrf
<input type="hidden" name="uuid" value="{{$uuid}}">
<div class="form-group">
    <label for="evaluate-title-input">标题</label>
    <input required type="text" class="form-control"  value="{{$qualification['title'] ?? old('title')}}" placeholder="标题" name="qualification[title]">
</div>
<div class="form-group">
    <label for="evaluate-title-input">描述</label>
    <input required type="text" class="form-control"  value="{{$qualification['desc'] ?? old('desc')}}" placeholder="描述" name="qualification[desc]">
</div>
<div class="form-group">
    <label for="evaluate-title-input">材料</label>
    <input required type="file" class="form-control"  value="{{$file ?? old('file')}}" placeholder="材料" name="file">
</div>
