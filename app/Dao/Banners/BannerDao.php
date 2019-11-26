<?php


namespace App\Dao\Banners;

use App\Models\Banner\Banner;
use App\Utils\Misc\ConfigurationTool;

class BannerDao
{

    /**
     * 根据学校ID 获取banner
     * @param $schoolId
     * @return mixed
     */
    public function getBannerBySchoolId($schoolId)
    {
       return Banner::where('school_id', $schoolId)
           ->orderBy('posit','asc')
           ->orderBy('sort','asc')
           ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    /**
     * 根据ID 获取banner
     * @param $id
     * @return mixed
     */
    public function getBannerById($id)
    {
        return Banner::find($id);
    }


    /**
     * 添加
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return Banner::create($data);
    }


    /**
     * 修改
     * @param $data
     * @return mixed
     */
    public function update($data)
    {
        return Banner::where('id', $data['id'])->update($data);
    }

}
