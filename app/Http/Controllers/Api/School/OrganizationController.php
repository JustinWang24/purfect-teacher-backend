<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/14
 * Time: 下午2:17
 */

namespace App\Http\Controllers\Api\School;


use App\Dao\Schools\OrganizationDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Utils\JsonBuilder;

class OrganizationController extends Controller
{

    /**
     * 获取组织和人员
     * @param MyStandardRequest $request
     * @return string
     */
    public function getOrganization(MyStandardRequest $request) {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $parentId = $request->get('parent_id');
        $dao = new OrganizationDao();
        $result = $dao->getByParentId($schoolId, $parentId);

        $data = [];
        foreach ($result as $key => $item) {
            $organ = [
                'id' => $item->id,
                'name' => $item->name,
                'level' => $item->level,
            ];
            $users = $item->members;
            $members = [];
            foreach ($users as $k => $val) {
                $members[$k]['user_id'] = $val->user_id;
                $members[$k]['name'] = $val->name;
                $members[$k]['title'] = $val->title;
            }

            $data[$key]['organ'] = $organ;
            $data[$key]['members'] = $members;
        }
        return JsonBuilder::Success($data);
    }

}