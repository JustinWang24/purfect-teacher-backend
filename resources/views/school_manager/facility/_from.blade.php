@csrf
<div class="form-group">
    <label for="facility-number-input">设备编号</label>
    <input required type="text" class="form-control" id="facility-number-input" value="{{$facility['facility_number'] ?? old('facility_number')}}" placeholder="设备编号" name="facility[facility_number]">
</div>
<div class="form-group">
    <label for="facility-name-input">设备名称</label>
    <input required type="text" class="form-control" id="facility-name-input" value="{{$facility['facility_name'] ?? old('facility_name')}}" placeholder="设备名称" name="facility[facility_name]">
</div>


<div class="form-group">
    <label for="facility-name-input">类型</label>

    <select required type="select" class="form-control" id="facility-type-select" value="" placeholder="类型" name="facility[type]">
        <option value="">请选择</option>
        @foreach($type as $key => $val)
        <option value="{{$val['id']}}"
                @if(isset($facility['type']))
                    @if($val['id'] == $facility['type']) selected @endif
                @endif >
                {{$val['val']}}
        </option>
        @endforeach
    </select>

</div>


<div class="form-group">
    <label for="facility-campus-select">校区</label>
    <select required type="text" class="form-control" id="facility-campus-select" value="" placeholder="校区" name="facility[campus_id]">
        <option value="">请选择</option>
        @foreach($campus as $key => $val)
            <option value="{{$val['id']}}"
                    @if(isset($facility['campus_id']))
                    @if($val['id'] == $facility['campus_id'])  selected @endif
                    @endif>
                {{$val['name']}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="facility-building-select">建筑</label>
    <select required type="text" class="form-control" id="facility-building-select" placeholder="建筑" name="facility[building_id]">
        <option value="">请选择</option>
        @foreach($building as $key => $val)
            <option value="{{$val['id']}}"
                    @if(isset($facility['building_id']))
                    @if($val['id'] == $facility['building_id'])  selected @endif
                    @endif>
                {{$val['name']}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="facility-room-select">教室</label>
    <select required type="text" class="form-control" id="facility-room-select" value="" placeholder="教室" name="facility[room_id]">
        <option value="">请选择</option>
        @foreach($room as $key => $val)
            <option value="{{$val['id']}}"
                    @if(isset($facility['room_id']))
                    @if($val['id'] == $facility['room_id'])  selected @endif
                    @endif>
                {{$val['name']}}</option>
        @endforeach
    </select>
</div>


<div class="form-group">
    <label for="building-addr-select">详细地址</label>
    <input  type="text" class="form-control" id="facility-addr-input" value="{{$facility['detail_addr'] ?? old('detail_addr')}}" placeholder="详细地址" name="facility[detail_addr]">
</div>
