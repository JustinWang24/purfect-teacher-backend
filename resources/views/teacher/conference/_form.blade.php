@csrf
<div class="form-group">
    <label for="conference-title-input">会议主题</label>
    <input required type="text" class="form-control" id="conference-title-input" value="{{$conference['title'] ?? old('title')}}" placeholder="会议主题" name="conference[title]">
</div>


<div class="form-group">
    <label for="conference-number-input">参会人员</label>
    <input required type="text"  class="form-control" id="conference-principal-input" value="{{$conference['conference'] ?? old('conference')}}" placeholder="参会人员" name="conference[conference]">
</div>

<div class="form-group">
    <label for="conference-number-input">会议室</label>

    <select required type="select" class="form-control" id="conference-room-select" value="" placeholder="类型" name="conference[room_id]">
        <option value="">请选择</option>
        @foreach($room as $key => $val)
        <option value="{{$val['id']}}"
                @if(isset($conference['room_id']))
                    @if($val['id'] == $conference['room_id']) selected @endif
                @endif >
                {{$val['name']}}
        </option>
        @endforeach
    </select>
</div>


<div class="form-group">
    <label for="conference-number-input">会议类型</label>

    <select required type="select" class="form-control" id="conference-type-select"  placeholder="类型" name="conference[type]">
        <option value="" class="form-control">请选择</option>
        @foreach($type as $key => $val)
        <option value="{{$key}}"
                @if(isset($conference['type']))
                    @if($key == $conference['type']) selected @endif
                @endif >
                {{$val}}
        </option>
        @endforeach
    </select>
</div>


<div class="form-group">
    <label for="conference-number-input">开始时间</label>
    <input required type="datetime-local" class="form-control" id="conference-from-input" value="{{$conference['from'] ?? old('from')}}" placeholder="开始时间" name="conference[from]">
</div>

<div class="form-group">
    <label for="conference-number-input">结束时间</label>
    <input required type="datetime-local" class="form-control" id="conference-to-input" value="{{$conference['to'] ?? old('to')}}" placeholder="结束时间" name="conference[to]">
</div>

<div class="form-group">
    <label for="conference-remark-input">备注</label>
    <textarea class="form-control" name="conference[remark]" cols="30" rows="10" id="conference-remark-input">
        {{$conference['remark'] ?? old('remark')}}
    </textarea>
</div>




