<?php
use App\Utils\UI\Anchor;
use App\Utils\UI\Button;
use App\User;
?>
<table class="table table-striped table-bordered table-hover table-checkable order-column valign-middle">
    <thead>
    <tr>
        <th>#</th>
        <th>学院名称</th>
        <th>院系数</th>
        <th>教职工数</th>
        <th>学生数</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($institutes as $index=>$institute)
        <tr>
            <td>{{ $index+1 }}</td>
            <td>
                {{ $institute->name }}
            </td>
            <td class="text-center">
                <a class="anchor-department-counter" href="{{ route('school_manager.institute.departments',['uuid'=>$institute->id,'by'=>'institute']) }}">{{ count($institute->departments) }}</a>
            </td>
            <td class="text-center">
                <a class="employees-counter" href="{{ route('school_manager.institute.users',['type'=>User::TYPE_EMPLOYEE,'by'=>'institute','uuid'=>$institute->id]) }}">{{ $institute->employeesCount() }}</a>
            </td>
            <td class="text-center">
                <a class="students-counter" href="{{ route('school_manager.institute.users',['type'=>User::TYPE_STUDENT,'by'=>'institute','uuid'=>$institute->id]) }}">{{ $institute->studentsCount() }}</a>
            </td>
            <td class="text-center">
                {{ Anchor::Print(['text'=>'编辑','class'=>'btn-edit-institute','href'=>route('school_manager.institute.edit',['uuid'=>$institute->id])], Button::TYPE_DEFAULT,'edit') }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>