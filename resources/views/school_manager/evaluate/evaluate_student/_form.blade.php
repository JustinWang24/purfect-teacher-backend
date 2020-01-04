@csrf
<div class="form-group">
    <label for="evaluate-title-input">标题</label>
    <input required type="text" class="form-control"  value="{{$evaluate['title'] ?? old('title')}}" placeholder="标题" name="evaluate[title]">
</div>

<div class="form-group">
    <label for="evaluate-number-input">状态</label>
    <select  name="evaluate[status]"  class="form-control" >
        @foreach($status as $key => $val)
             <option value="{{$key}}"
                     @if(isset($evaluate))
                        @if($key == $evaluate['status']) selected @endif
                    @endif>
            {{$val}}
             </option>
        @endforeach
    </select>
</div>
