<?php


namespace App\Http\Controllers\Api\Location;


use App\Http\Requests\MyStandardRequest;
use App\Models\Area;
use App\Utils\JsonBuilder;
use App\Dao\Location\AreaDao;
use App\Http\Controllers\Controller;

class AreaController extends Controller
{

    /**
     * 获取省份列表
     * @return string
     */
    public function getProvinces() {
        $dao = new AreaDao();
        $list = $dao->getAreaList();
        $data = ['provinces'=>$list];
        return JsonBuilder::Success($data);
    }


    /**
     * 城市列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function getCities(MyStandardRequest $request) {
        $parentId = $request->get('parent_id');
        if(empty($parentId)) {
            return JsonBuilder::Error('parent_id不能为空');
        }
        $dao = new AreaDao();
        $list = $dao->getAreaList($parentId, Area::LEVEL_CITIES);
        $data = ['cities'=>$list];
        return JsonBuilder::Success($data);
    }


    /**
     * 获取区县
     * @param MyStandardRequest $request
     * @return string
     */
    public function getDistricts(MyStandardRequest $request) {
        $parentId = $request->get('parent_id');
        if(empty($parentId)) {
            return JsonBuilder::Error('parent_id不能为空');
        }
        $dao = new AreaDao();
        $list = $dao->getAreaList($parentId, Area::LEVEL_DISTRICTS);
        $data = ['districts'=>$list];
        return JsonBuilder::Success($data);
    }
}
