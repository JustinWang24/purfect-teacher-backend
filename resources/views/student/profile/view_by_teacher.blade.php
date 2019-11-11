<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
/**
 * @var \App\Models\Students\StudentProfile $profile
 */
/**
 * @var \App\Models\RecruitStudent\RegistrationInformatics $registration
 */
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-head">
                    <header class="full-width">
                        <p class="pull-left m-0" style="line-height: 40px;">报名学生资料管理: ({{ $plan->title }}) - {{ $student->name }}</p>
                        @if($registration->status === \App\Models\RecruitStudent\RegistrationInformatics::WAITING)
                            <?php
                            Anchor::PrintCircle(['text'=>'通过审核','class'=>'pull-right ml-4'],Button::TYPE_SUCCESS);
                            Anchor::PrintCircle(['text'=>'拒绝此报名申请','class'=>'pull-right btn-need-confirm'],Button::TYPE_DANGER);
                            ?>
                        @endif
                    </header>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="profile-sidebar">
                @include('student.elements.sidebar.avatar')
                @include('student.elements.sidebar.about_student')
            </div>
            <!-- 报名时的表格 -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-12">
                    @include('student.elements.form.registration')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
