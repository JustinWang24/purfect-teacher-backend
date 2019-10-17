<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/10/19
 * Time: 2:25 PM
 */

namespace App\Dao\Schools;
use App\User;
use Illuminate\Support\Facades\DB;
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
}