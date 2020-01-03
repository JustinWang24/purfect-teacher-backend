<?php


namespace App\Dao\Evaluate;


use App\Models\Evaluate\EvaluateStudent;

class EvaluateStudentDao
{

    /**
     * @param $userId
     * @param $year
     * @param $type
     * @return mixed
     */
    public function getStudentByUserId($userId, $year, $type) {
        $map = ['user_id'=>$userId, 'year'=>$year, 'type'=>$type];
        return EvaluateStudent::where($map)->get();
    }


    /**
     * @param $userId
     * @param $status
     * @return mixed
     */
    public function getStudentByUserAndStatus($userId, $status) {
        $map = ['user_id'=>$userId, 'status'=>$status];
        return EvaluateStudent::where($map)
            ->select(['year','type'])
            ->orderBy('created_at', 'desc')
//            ->distinct(['year','type'])
            ->first();
    }
}
