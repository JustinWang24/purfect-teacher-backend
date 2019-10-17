<?php
namespace App\Dao\Schools;

use App\User;
use App\Models\School;

class SchoolDao
{
    private $currentUser;
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    public function getMySchools($onlyFirstOne = false){
        if($this->currentUser->isOperatorOrAbove()){
            return School::all();
        }
    }

    /**
     * 更新学校记录
     * @param $schoolData
     * @param array $extra
     * @return bool
     */
    public function updateSchool($schoolData, $extra = []){
        if($this->currentUser->isSuperAdmin()){
            // 只有超级管理员能更新
            $school = $this->getSchoolByUuid($schoolData['uuid']);
            if($school){
                unset($schoolData['uuid']);
                foreach ($schoolData as $fieldName=>$fieldValue) {
                    $school->$fieldName = $fieldValue;
                }
                return $school->save();
            }
        }
        return false;
    }

    /**
     * @param $uuid
     * @return School
     */
    public function getSchoolByUuid($uuid){
        return School::where('uuid', $uuid)->first();
    }

    /**
     * @param $id
     * @return School
     */
    public function getSchoolById($id){
        return School::find($id);
    }
    
    /**
     * 根据 uuid 或者 id 获取学校
     * @param $idOrUuid
     * @return School|null
     */
    public  function getSchoolByIdOrUuid($idOrUuid)
    {
        if(is_string($idOrUuid) && strlen($idOrUuid) > 10){
            return $this->getSchoolByUuid($idOrUuid);
        }
        elseif (is_int($idOrUuid)){
            return $this->getSchoolById($idOrUuid);
        }
        return null;
    }
}