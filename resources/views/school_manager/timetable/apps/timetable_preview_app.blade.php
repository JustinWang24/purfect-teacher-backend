<div id="current-school-id" data-school="{{ $school->id }}"></div>
<div class="container-fluid" id="school-timetable-previewer-app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <timetable-item-form
                    school-id="{{ $school->id }}"
                    user-uuid="{{ Auth::user()->uuid }}"
                    :time-slots="timeSlots"
                    v-on:new-item-created="newItemCreatedHandler"
                    v-on:item-updated="itemUpdatedHandler"
                    v-on:timetable-refresh="refreshTimetableHandler"
                    :reloading="reloading"
                    :init-weekday-index="shared.initWeekdayIndex"
                    :init-time-slot-id="shared.initTimeSlotId"
                    :shared="shared"
                ></timetable-item-form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <timetable-previewer
                    school-id="{{ $school->id }}"
                    :timetable="timetable"
                    :time-slots="timeSlots"
                    :sub-title="subTitle"
                    v-on:create-new-by-click="createNewByClickHandler"
                ></timetable-previewer>
            </div>
        </div>
    </div>
</div>