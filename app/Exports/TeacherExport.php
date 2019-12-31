<?php

namespace App\Exports;

use App\Models\Teachers\TeacherProfile;
use Maatwebsite\Excel\Concerns\FromCollection;

class TeacherExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $profile = TeacherProfile::all();
        $output = [];
        $category_teach = [
            1 =>'文化课教师',
            2 =>'公共课教师',
            3 =>'专业课教师',
        ];

        $category_major = [
            1   => '交通运输',
            2   => '农林牧渔',
            3   => '旅游服务',
            4   => '土木水利',
            5   => '文化教育',
            6   => '信息技术',
            7   => '财经商贸',
            8   => '医药卫生',
        ];
        foreach ($profile as $key => $row) {
            $output[$key]['id'] = $row->id;
            $output[$key]['gender'] = $row->gender==1?'男':'女';
            $output[$key]['id_number'] = $row->id_number."\t";
            $output[$key]['birthday'] = $row->birthday;
            $output[$key]['joined_at'] = $row->joined_at;//入职日期
            $output[$key]['political_name'] = $row->political_name;//政治面貌
            $output[$key]['nation_name'] = $row->nation_name;//民族
            $output[$key]['degree'] = $row->degree;//学位
            $output[$key]['work_start_at'] = $row->work_start_at;//参加工作时间
            $output[$key]['major'] = $row->major;//第一学历专业
            $output[$key]['final_education'] = $row->major;//最高学历
            $output[$key]['final_major'] = $row->final_major;//最高学历专业
            $output[$key]['hired'] = $row->hired?'聘任':'非聘任';//聘任
            $output[$key]['category_teach'] = $row->category_teach?$category_teach[$row->category_teach]:'';//授课类别
            $output[$key]['category_major'] = $row->category_major?$category_major[$row->category_major]:'';//职业授课类别
        }
        array_unshift($output, ['ID','性别','身份证','生日','入职日期','政治面貌','民族','学位','参加工作时间','第一学历专业','最高学历','最高学历专业','聘任']);
        return collect($output);
    }
}
