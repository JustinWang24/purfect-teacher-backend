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
    public function getStatusByUserId($userId, $year, $type) {
        $map = ['user_id'=>$userId, 'year'=>$year, 'type'=>$type];
        return EvaluateStudent::where($map)->get();
    }
}
