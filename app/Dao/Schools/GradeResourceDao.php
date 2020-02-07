<?php


namespace App\Dao\Schools;


use App\Models\Schools\GradeResource;

class GradeResourceDao
{
    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return GradeResource::create($data);
    }

    /**
     * 根据 ID 删除
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return GradeResource::where('id', $id)->delete();
    }
}
