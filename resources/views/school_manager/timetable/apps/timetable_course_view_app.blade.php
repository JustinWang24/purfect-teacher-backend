<div id="timetable-current-school-id" data-school="{{ $school->id }}"></div>
<div id="timetable-current-course-id" data-id="{{ $course->id }}"></div>
<div id="timetable-current-course-name" data-name="{{ $course->name }}"></div>
<div id="timetable-current-year" data-year="{{ date('Y') }}"></div>
<div id="timetable-current-term" data-term="{{ $term }}"></div>
<div class="container-fluid" id="school-timetable-course-viewer-app">
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