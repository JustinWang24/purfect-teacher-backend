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


//    public function
}