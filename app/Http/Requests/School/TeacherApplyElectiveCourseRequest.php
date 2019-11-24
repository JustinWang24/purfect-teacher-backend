<?php
namespace App\Http\Requests\School;


use App\Http\Requests\MyStandardRequest;

class TeacherApplyElectiveCourseRequest extends MyStandardRequest
{
    public function rules()
    {
        return [
            'course.id'             => 'nullable|numeric',
            'course.code'           => ['required',  'max:255'],
            'course.scores'         => ['required',  'numeric'],
            'course.max_num'        => ['required',  'numeric'],
            'course.open_num'       => ['required',  'numeric'],
            'course.name'           => ['required',  'max:255'],
            'course.term'           => ['required',  'numeric'],
            'course.year'           => ['required',  'numeric'],
            'course.start_year'     => ['nullable',  'numeric'],
            'course.majors'         => ['nullable',  'array'],
            'course.teacher_id'     => ['nullable',  'numeric'],
            'course.teacher_name'   => ['nullable',  'max:255'],
            'course.desc'           => ['required',  'max:255'],
            'course.school_id'      => ['required',  'max:255'],
            'schedule.*.weeks'      => ['required',  'array'],
            'schedule.*.days'       => ['required',  'array'],
            'schedule.*.timeSlots'  => ['required',  'array'],
            'schedule.*.building_id'=> ['nullable', 'numeric'],
            'schedule.*.building_name'  => ['nullable', 'max:255'],
            'schedule.*.classroom_id'   => ['nullable', 'numeric'],
            'schedule.*.classroom_name' => ['nullable', 'max:255'],
        ];
    }
}
