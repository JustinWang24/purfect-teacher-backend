<?php
namespace App\Dao\Teachers;

use App\Models\Teachers\Exam;

class ExamDao
{

    /**
     * 创建考试
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return Exam::create($data);
    }


    /**
     * 获取考试列表
     * @param $map
     * @return mixed
     */
    public function getExam($map)
    {
        return Exam::where($map)->get();
    }


    /**
     * 修改考试
     * @param $map
     * @param $data
     * @return mixed
     */
    public function updExam($map,$data)
    {
        return Exam::where($map)->update($data);
    }




}