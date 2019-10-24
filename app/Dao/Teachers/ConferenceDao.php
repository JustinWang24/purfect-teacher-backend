<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午4:04
 */
namespace App\Dao\Teachers;

use App\Models\Teachers\Conference;

class ConferenceDao
{

    public function getList($map)
    {
        $model = new Conference();
        $list = $model->where($map)->with('users')->get();
        return $list;
    }


    /**
     * 创建会议
     * @param $data
     * @return mixed
     */
    public function addConference($data)
    {
        return Conference::create($data);
    }



}