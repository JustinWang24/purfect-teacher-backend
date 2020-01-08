@php
    use App\Utils\UI\Anchor;
    use App\Utils\UI\Button;
    use App\User;
    use App\Utils\Misc\ConfigurationTool;
    /**
     * @var \App\User $teacher
     */
@endphp
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-2 col-lg-2 col-xl-2">
            @if(count($applications) > 0)
                @foreach($applications as $index=>$application)
                    @if($application->id != $application_id)
                    <div class="card">
                        <div class="card-head">
                            <header>{{ $application->name }}</header>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <p>申请人: {{ $application->teacher_name }}</p>
                            </div>
                            <div class="row">
                                <p class="{{ $application->getStatusColor() }}">{!! $application->getStatusText() !!}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <p>最近 3 个月没有其他申请</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
            <div class="card">
                <div class="card-head">
                    <header>
                        申请开设一门选修课: 申请人({{ @$teacher->name }})
                    </header>
                </div>
                <div class="card-body">
                    <div id="app-init-data-holder"
                         data-school="{{ session('school.id') }}"
                         data-application="{{ $application_id??null }}"
                    ></div>
                    <div class="row" id="teacher-apply-a-new-elective-course-app">
                        <div class="col-12">
                            <elective-course-form
                                :course-model="course"
                                :application-id="applicationId"
                                :total-weeks="{{ $configuration->study_weeks_per_term }}"
                                :time-slots="timeSlots"
                                :majors="majors"
                                :as-admin="true"
                                school-id="{{ session('school.id') }}"
                                school-uuid="{{ session('school.uuid') }}"
                            ></elective-course-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
