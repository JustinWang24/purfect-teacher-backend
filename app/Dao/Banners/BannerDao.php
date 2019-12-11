<?php


namespace App\Dao\Banners;

use App\Models\Banner\Banner;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Database\Eloquent\Collection;

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

    public function delete($id)
    {
        return Banner::where('id',$id)->delete();
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


    /**
     * 根据学校 位置 获取banner
     * @param $schoolId
     * @param $posit
     * @param $publicOnly : 为真表示只能登陆可看
     * @return Collection
     */
    public function getBannerBySchoolIdAndPosit($schoolId, $posit, $publicOnly = false)
    {
        $where = ['school_id' => $schoolId, 'posit' => $posit, 'status' => Banner::STATUS_OPEN];
        if($publicOnly){
            $where['public'] = true;
        }
       return Banner::where($where)
           ->select(['id', 'type', 'title', 'image_url','content','external'])
           ->orderBy('sort','asc')
           ->get();
    }
}
