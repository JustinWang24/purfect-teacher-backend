<div class="row" id="school-courses-manager-app">
    <div class="col-12">
        <div class="card">
            <courses-manager school-id="{{ $school->id }}" :can-delete="{{ Auth::user()->isSchoolAdminOrAbove() ? 'true' : 'false' }}"></courses-manager>
        </div>
    </div>
</div>