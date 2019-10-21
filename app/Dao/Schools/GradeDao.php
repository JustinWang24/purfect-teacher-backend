<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 2:53 PM
 */

namespace App\Dao\Schools;
use App\User;
use App\Models\Users\GradeUser;
use App\Models\Schools\Grade;

class GradeDao
{
    private $currentUser;
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    public function getGradeById($id){
        return Grade::find($id);
    }
}