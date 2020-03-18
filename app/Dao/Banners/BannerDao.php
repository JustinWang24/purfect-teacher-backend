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
     * @param $param
     * @return mixed
     */
    public function getBannerBySchoolId($schoolId, $param = [])
    {
      $condition[] = ['id', '>', 0];
      if (is_numeric($param['app'])) {
        $condition[] = ['app', '=', (Int)$param['app']];
      }
      if (is_numeric($param['status'])) {
        $condition[] = ['status', '=', (Int)$param['status']];
      }
      return Banner::where('school_id', $schoolId)->where($condition)
        ->orderBy('app', 'asc')
        ->orderBy('sort', 'asc')
        ->orderBy('id', 'desc')
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
           ->orderBy('sort', 'asc')
           ->orderBy('id', 'desc')
           ->limit(5)->get();
    }


    /**
     * Func 获取类型
     * @return array
     */
    public function getBannerTypeListInfo()
    {
      $data = [];
      $obj = new Banner();
      // 获取终端
      $getAppArr = $obj->getAppArr();
      foreach ($getAppArr as $key => $val) {
        $data[] = ['value' => $key, 'label' => $val];
      }
      // 获取位置
      foreach ($data as $key1 => &$val1) {
        $data2 = [];
        $getPositArr = $obj->getPositArr($val1['value']);
        if(!empty($getPositArr))
        {
          foreach ($getPositArr as $key2 => $val2) {
            $data2[] = ['value' => $key2, 'label' => $val2];
          }
          if(!empty($data2)) {
            foreach ($data2 as $key3 => &$val3) {
              $data3 = [];
              $getTypeArr = $obj->getTypeArr($val3['value']);
              if(!empty($getTypeArr)){
                foreach ($getTypeArr as $key4=>$val4)
                {
                  $data3[] = ['value' => $key4, 'label' => $val4];
                }
              }
              $val3['children'] = $data3;
            }
          }
        }
        $val1['children'] = $data2;
      }
      return $data;
    }


}
