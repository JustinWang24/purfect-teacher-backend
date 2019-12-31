<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <form action="{{ route('school_manager.evaluate.evaluate-teacher.create') }}" method="post" id="add-evaluate-teacher-form">
                                @csrf
                                <input type="hidden" name="evaluate-teacher[grade_id]" value="{{ $grade_id }}">

                                <label for="evaluate-year-select">学年</label>
                                <select name="evaluate-teacher[year]" class="form-control" required>
                                    <option value="">请选择学年</option>
                                    @foreach($year as $key => $val)
                                    <option value="{{ $val}}">{{ $val }}</option>
                                    @endforeach
                                </select>

                                <label for="evaluate-year-select">学期</label>
                                <select name="evaluate-teacher[term]" class="form-control" required>
                                    <option value="">请选择学期</option>
                                    @foreach($type as $key => $val)
                                    <option value="{{ $key}}">{{ $val }}</option>
                                    @endforeach
                                </select>


                                <table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>学号</th>
                                        <th>头像</th>
                                        <th>姓名</th>
                                        <th>性别</th>
                                        <th>联系电话</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($students as $index=>$gradeUser)
                                        @php
                                            /** @var \App\Models\Users\GradeUser $gradeUser */
                                        @endphp
                                        <tr>
                                            <td><input type="checkbox" name="evaluate-teacher[user_id][]" value="{{ $gradeUser->user_id }}"></td>
                                            <td>{{ $gradeUser->studentProfile->serial_number }}</td>
                                            <td>
                                                <img src="{{ $gradeUser->studentProfile->avatar }}" style="width: 60px;border-radius: 50%;">
                                            </td>
                                            <td>{{ $gradeUser->user->name ?? 'n.a' }}</td>
                                            <td>{{ $gradeUser->studentProfile->getGenderTextAttribute() }}</td>
                                            <td>
                                                {{ $gradeUser->user->mobile }}
                                                {{ $gradeUser->user->getStatusText() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <?php
                                Button::Print(['id'=>'btn-edit-evaluate','text'=>trans('general.submit')], Button::TYPE_PRIMARY);
                                ?>&nbsp;
                                <?php
                                Anchor::Print(['text'=>trans('general.return'),'href'=>url()->previous(),'class'=>'pull-right link-return'], Button::TYPE_SUCCESS,'arrow-circle-o-right')
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
