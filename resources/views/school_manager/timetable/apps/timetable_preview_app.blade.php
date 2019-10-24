<div class="row" id="school-timetable-previewer-app">
    <div class="col-4">
        <div class="card">
            <timetable-item-form school-id="{{ $school->id }}">
            </timetable-item-form>
        </div>
    </div>
    <div class="col-8">
        <div class="card">
            <timetable-previewer school-id="{{ $school->id }}">
            </timetable-previewer>
        </div>
    </div>
</div>