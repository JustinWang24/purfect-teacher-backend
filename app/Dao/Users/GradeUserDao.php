<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 9:20 PM
 */

namespace App\Dao\Users;
use App\Models\Users\GradeUser;
use Illuminate\Database\Eloquent\Collection;

class GradeUserDao
{
    /**
     * 根据给定的校园 id 值, 获取用户信息
     * @param $campusId
     * @param $type
     * @return Collection
     */
    public function paginateUserByCampus($campusId, $type){
        return $this->_paginateUsersBy($type,'campus_id', $campusId);
    }

    /**
     * @param $type
     * @param $fieldName
     * @param $fieldValue
     * @param string $orderBy
     * @param string $direction
     * @return Collection
     */
    private function _paginateUsersBy($type, $fieldName, $fieldValue, $orderBy = 'updated_at', $direction = 'desc'){
        return GradeUser::where($fieldName,$fieldValue)
            ->where('user_type',$type)
            ->orderBy($orderBy,$direction)->paginate();
    }
}