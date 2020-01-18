<?php

use Illuminate\Database\Seeder;

class TeacherAttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendance1 = \App\Models\TeacherAttendance\Attendance::create([
            'school_id' => 1,
            'title' => '五天无中午',
            'wifi_name' => 'wifi1',
            'using_afternoon' => 0,
        ]);
        DB::table('teacher_attendance_organizations')->insert([
            'teacher_attendance_id' => $attendance1->id,
            'organization_id' => 61
        ]);
        \App\Models\TeacherAttendance\ExceptionDay::create([
            'teacher_attendance_id' => $attendance1->id,
            'day' => '2020-01-19'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance1->id,
            'week' => 'Monday',
            'start' => '08:00:00',
            'end' => '20:00:00',
            'morning' => '09:00:00',
            'morning_late' => '09:30:00',
            'evening' => '18:00:00'
        ]);

        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance1->id,
            'week' => 'Tuesday',
            'start' => '08:02:00',
            'end' => '20:02:00',
            'morning' => '09:02:00',
            'morning_late' => '09:32:00',
            'evening' => '18:02:00'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance1->id,
            'week' => 'Wednesday',
            'start' => '08:03:00',
            'end' => '20:03:00',
            'morning' => '09:03:00',
            'morning_late' => '09:33:00',
            'evening' => '18:03:00'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance1->id,
            'week' => 'Thursday',
            'start' => '08:04:00',
            'end' => '20:04:00',
            'morning' => '09:04:00',
            'morning_late' => '09:34:00',
            'evening' => '18:04:00'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance1->id,
            'week' => 'Friday',
            'start' => '08:05:00',
            'end' => '20:05:00',
            'morning' => '09:05:00',
            'morning_late' => '09:35:00',
            'evening' => '18:05:00'
        ]);


        $attendance2 = \App\Models\TeacherAttendance\Attendance::create([
            'school_id' => 1,
            'title' => '六天有中午',
            'wifi_name' => 'wifi2',
            'using_afternoon' => 1,
        ]);
        DB::table('teacher_attendance_organizations')->insert([
            'teacher_attendance_id' => $attendance2->id,
            'organization_id' => 62
        ]);
        DB::table('teacher_attendance_organizations')->insert([
            'teacher_attendance_id' => $attendance2->id,
            'organization_id' => 63
        ]);
        \App\Models\TeacherAttendance\ExceptionDay::create([
            'teacher_attendance_id' => $attendance2->id,
            'day' => '2020-01-19'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance2->id,
            'week' => 'Monday',
            'start' => '08:10:00',
            'end' => '20:10:00',
            'morning' => '09:10:00',
            'afternoon' => '13:10:00',
            'afternoon_late' => '13:40:00',
            'morning_late' => '09:40:00',
            'evening' => '18:10:00'
        ]);

        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance2->id,
            'week' => 'Tuesday',
            'start' => '08:20:00',
            'end' => '20:20:00',
            'morning' => '09:20:00',
            'morning_late' => '09:20:00',
            'afternoon' => '13:20:00',
            'afternoon_late' => '13:42:00',
            'evening' => '18:20:00'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance2->id,
            'week' => 'Wednesday',
            'start' => '08:30:00',
            'end' => '20:30:00',
            'morning' => '09:30:00',
            'morning_late' => '09:40:00',
            'afternoon' => '13:30:00',
            'afternoon_late' => '13:43:00',
            'evening' => '18:30:00'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance2->id,
            'week' => 'Thursday',
            'start' => '08:40:00',
            'end' => '20:40:00',
            'morning' => '09:40:00',
            'morning_late' => '09:50:00',
            'afternoon' => '13:40:00',
            'afternoon_late' => '13:44:00',
            'evening' => '18:40:00'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance2->id,
            'week' => 'Friday',
            'start' => '08:50:00',
            'end' => '20:50:00',
            'morning' => '09:50:00',
            'morning_late' => '09:59:00',
            'afternoon' => '13:50:00',
            'afternoon_late' => '13:55:00',
            'evening' => '18:50:00'
        ]);
        \App\Models\TeacherAttendance\Clockset::create([
            'teacher_attendance_id' => $attendance2->id,
            'week' => 'Saturday',
            'start' => '08:06:00',
            'end' => '20:06:00',
            'morning' => '09:06:00',
            'morning_late' => '09:36:00',
            'afternoon' => '13:55:00',
            'afternoon_late' => '13:56:00',
            'evening' => '18:06:00'
        ]);

    }
}
