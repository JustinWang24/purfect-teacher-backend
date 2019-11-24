<div id="timetable-current-school-id" data-school="{{ $school->id }}"></div>
<div id="timetable-current-teacher-id" data-id="{{ $teacher->id }}"></div>
<div id="timetable-current-teacher-name" data-name="{{ $teacher->name }}"></div>
<div id="timetable-current-year" data-year="{{ date('Y') }}"></div>
<div id="timetable-current-term" data-term="{{ $term }}"></div>
<div class="container-fluid" id="school-timetable-teacher-viewer-app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <timetable-previewer
                        school-id="{{ $school->id }}"
                        user-uuid="{{ Auth::user()->uuid }}"
                        :timetable="timetable"
                        :time-slots="timeSlots"
                        :sub-title="subTitle"
                        :week-type="weekType"
                        :as-manager="{{ !Auth::user()->isSchoolAdminOrAbove() ? 'true' : 'false' }}"
                        v-on:timetable-refresh="refreshTimetableHandler"
                ></timetable-previewer>
            </div>
        </div>
    </div>
</div>