<?php
namespace App\Dao\Location;
use App\Models\Area;

class AreaDao
{

    /**
     * 查询地区
     * @param int $parentId
     * @return mixed
     */

    /**
     * @param int $parentId 父级id
     * @param int $level  级别
     * @return mixed
     */
    public function getAreaList($parentId = 0, $level=Area::LEVEL_PROVINCES) {
        $field = ['linkageid as id','name'];
        $map = ['parentid'=>$parentId, 'level'=>$level];
        $result =  Area::where($map)->select($field)->get();

        if(count($result) === 0){
            // 什么也没有找到, 那么就要查一下, 是否提交的 $parentId 是省或者城市的名字了
            if($level === Area::LEVEL_CITIES){
                // 在搜寻某个省所包含的城市名
                $province = Area::where('name','=',$parentId)->first();
                if($province){
                    $map = ['parentid'=>$province->linkageid, 'level'=>$level];
                    $result =  Area::where($map)->select($field)->get();
                }
            }
            elseif ($level === Area::LEVEL_DISTRICTS){
                // 搜索城市所包含的城区
                $city = Area::where('name','=',$parentId)->first();
                if($city){
                    $map = ['parentid'=>$city->linkageid, 'level'=>$level];
                    $result =  Area::where($map)->select($field)->get();
                }
            }
        }

        return $result;
    }
}
