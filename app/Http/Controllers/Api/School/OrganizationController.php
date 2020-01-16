<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/14
 * Time: 下午2:17
 */

namespace App\Http\Controllers\Api\School;


use App\Dao\Schools\OrganizationDao;
use App\Dao\Users\UserOrganizationDao;
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
        $keyword = $request->get('keyword');
        $dao = new OrganizationDao();
        if(!empty($keyword)) {
            $result = $dao->getOrganByName($schoolId, $keyword);
            $parentId = 0;
        } else {
            $result = $dao->getByParentId($schoolId, $parentId);
        }

        $organ = [];

        foreach ($result as $key => $item) {
            $organ[$key]['id'] =$item->id;
            $organ[$key]['name'] =$item->name;
            $organ[$key]['level'] =$item->level;
            $branches = $item->branch;
            $organ[$key]['status'] = true;
            if(count($branches) == 0) {
                $organ[$key]['status'] = false;
            }
        }
        $userOrganDao = new UserOrganizationDao();
        $members = $userOrganDao->getOrganUserByOrganId($schoolId, $parentId);
        $data = [
            'organ' => $organ,
            'members' => $members
        ];
        return JsonBuilder::Success($data);
    }

}