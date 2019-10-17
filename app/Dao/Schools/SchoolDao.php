<?php

namespace App\Dao\Schools;

use App\User;
use App\Models\Schools\School;


class SchoolDao
{
    /**
     * 根据 uuid 或者 id 获取学校
     * @param $idOrUuid
     * @return School|null
     */
    public  function getSchoolByIdOrUuid($idOrUuid)
    {
        if(is_string($idOrUuid) && strlen($idOrUuid) > 10){
            return School::where('uuid', $idOrUuid)->first();
        }
        elseif (is_int($idOrUuid)){
            return School::find($idOrUuid);
        }
        return null;
    }



}
