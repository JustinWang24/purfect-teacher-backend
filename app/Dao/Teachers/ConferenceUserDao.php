<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/24
 * Time: 上午9:58
 */
namespace App\Dao\Teachers;

use App\Models\Teachers\ConferencesUser;

class ConferenceUserDao{



    public function getConferenceUser($from,$to,$schoolId)
    {
        $map = [['school_id', '=', $schoolId],['from', '>=', $from]];
        $map2 = [['school_id', '=', $schoolId],['to', '<=', $to]];
        return ConferencesUser::where($map)->orwhere($map2)->get()->toArray();
    }



    /**
     * 创建参会人员
     * @param $data
     * @return mixed
     */
    public function addConferenceUser($data)
    {

        return ConferencesUser::create($data);
    }
}