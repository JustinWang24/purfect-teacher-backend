<?php


namespace App\Models\NetworkDisk;


trait CanGetByUuid
{
    /**
     * 根据 Uuid 获取数据
     * @param $uuid
     * @param array|null $fields
     * @return mixed
     */
    public static function GetByUuid($uuid, $fields = null){
        if(is_string($uuid) && strlen($uuid) > 20){
            if($fields){
                return self::select($fields)
                    ->where('uuid',$uuid)->first();
            }
            return self::where('uuid',$uuid)->first();
        }
        return null;
    }

    /**
     * 根据 Id 获取数据
     * @param $id
     * @param array|null $fields
     * @return mixed
     */
    public static function GetById($id, $fields = null){
        if($fields){
            return self::select($fields)
                ->where('id', $id)->first();
        }
        return self::where('id', $id)->first();
    }
}
