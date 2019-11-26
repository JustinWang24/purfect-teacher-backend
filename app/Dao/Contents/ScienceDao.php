<?php


namespace App\Dao\Contents;


use App\Models\Contents\Science;
use App\Utils\Misc\ConfigurationTool;

class ScienceDao
{
    public function create($data) {
        return Science::create($data);
    }


    public function getScienceListBySchoolId($schoolId) {
        return Science::where('school_id', $schoolId)->orderBy('id')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 获取详情
     * @param $id
     * @return mixed
     */
    public function getScienceById($id) {
        return Science::where('id', $id)->first();
    }


    /**
     * 编辑
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateScienceById($id, $data) {
        return Science::where('id',$id)->update($data);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return Science::where('id', $id)->delete();
    }


}
