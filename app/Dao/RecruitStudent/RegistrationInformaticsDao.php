<?php


namespace App\Dao\RecruitStudent;

use App\Models\RecruitStudent\RegistrationInformatics;

class RegistrationInformaticsDao
{
    public function getRegistrationInformatics($field)
    {
        return RegistrationInformatics::select($field)->get();
    }


}
