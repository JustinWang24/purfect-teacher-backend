<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 11:53 AM
 */

namespace App\Dao\Schools;
use App\User;
use App\Models\Schools\Institute;
use Illuminate\Database\Eloquent\Collection;

class InstituteDao
{
    private $currentUser;
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    /**
     * 根据给定的 campus 或 id 获取包含的学院
     * @param $campus
     * @return Collection
     */
    public function getByCampus($campus){
        if(is_object($campus)){
            $campus = $campus->id;
        }
        return Institute::where('campus_id',$campus)->get();
    }

    /**
     * @param $id
     * @return Institute|null
     */
    public function getInstituteById($id){
        return Institute::find($id);
    }
}