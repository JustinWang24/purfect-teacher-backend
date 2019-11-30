<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 5/11/19
 * Time: 5:37 PM
 */

namespace App\Dao;


use Illuminate\Database\Eloquent\Model;

trait BuildFillableData
{

    /**
     * 根据给定的的模型类, 获取其可以插入到数据库中的字段, 生成数组数据, 然后返回
     *
     * @param Model $model
     * @param $data
     * @return array
     */
    public function getFillableData($model, $data){
        $fillable = $model->getFillable();

        $filledData = [];
        foreach ($fillable as $fieldName) {
            if(isset($data[$fieldName])){
                $filledData[$fieldName] = $data[$fieldName];
            }
        }
        return $filledData;
    }
}