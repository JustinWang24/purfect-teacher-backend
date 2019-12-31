<div class="form-group">
    <label for="evaluate-teacher-select">教师</label>
    <select name="evaluate-teacher[teacher]" class="form-control">
        <option value="">请选择教师</option>
    @foreach($teacher as $key => $val)
        <option value="{{ $val->id }}">{{ $val->name }}</option>
        @endforeach
    </select>



    <label for="evaluate-year-select">班级</label>
        <input required type="number" min="1" max="10" class="form-control" id="evaluate-number-input" value="" placeholder="班级" name="">
    <label for="evaluate-year-select">学生</label>
        <input required type="number" min="1" max="10" class="form-control" id="evaluate-number-input" value="" placeholder="学生" name="">

</div>
