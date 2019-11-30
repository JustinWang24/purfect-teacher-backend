<div class="row" id="school-courses-manager-app">
    <div class="col-12">
        <div class="card">
            <courses-manager
                    user-uuid="{{ Auth::user()->uuid }}"
                    school-id="{{ $school->id }}"
                    school-uuid="{{ $school->uuid }}"
                    :can-delete="{{ Auth::user()->isSchoolAdminOrAbove() ? 'true' : 'false' }}"
            ></courses-manager>
        </div>
    </div>
</div>