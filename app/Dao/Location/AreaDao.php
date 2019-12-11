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
        return Area::where($map)->select($field)->get();
    }
}
