@csrf
<div class="form-group">
    <label for="evaluate-title-input">标题</label>
    <input required type="text" class="form-control" id="evaluate-title-input" value="{{$evaluate['title'] ?? old('title')}}" placeholder="标题" name="evaluate[title]">
</div>

<div class="form-group">
    <label for="evaluate-number-input">分数</label>
    <input required type="number" min="1" max="10" class="form-control" id="evaluate-number-input" value="{{$evaluate['score'] ?? old('score')}}" placeholder="分数" name="evaluate[score]">
</div>

<div class="form-group">
    <label for="evaluate-number-input">对谁评价</label>
    <select  name="evaluate[type]"  class="form-control" >
        @foreach($type as $key => $val)
             <option value="{{$key}}"
                     @if(isset($evaluate))
                        @if($key == $evaluate['type']) selected @endif
                    @endif>
            {{$val}}
             </option>
        @endforeach
    </select>
</div>

