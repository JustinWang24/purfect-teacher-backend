@extends('layouts.app')
@section('content')
    <div class="row" id="teacher-oa-approvals-app">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        审批
                    </header>
                </div>
                <div class="card-body">
                    <p>resources/teacher/ly/oa/approvals.blade.php</p>
                </div>
            </div>
        </div>
    </div>

    <div id="app-init-data-holder"
         data-school="{{ session('school.id') }}"
    ></div>
@endsection
