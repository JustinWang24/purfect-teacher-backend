<?php
namespace App\Dao\Schools;

use App\User;
use App\Models\School;
use Ramsey\Uuid\Uuid;
use App\Models\Schools\Campus;
use Illuminate\Http\Request;
class SchoolDao
{
    private $currentUser;
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    public function getMySchools($onlyFirstOne = false){
        if($this->currentUser->isOperatorOrAbove()){
            return School::orderBy('updated_at','desc')->get();
        }elseif ($this->currentUser->isSchoolAdminOrAbove()) {
            return $this->getSchoolManagerDefaultSchool();
        }
    }

    public function getSchoolManagerDefaultSchool(){
        return School::find($this->currentUser->getSchoolId());
    }

    /**
     * @param $schoolData
     * @param array $extra
     * @return bool
     */
    public function createSchool($schoolData, $extra = []){
        if($this->currentUser->isSuperAdmin()){
            // 只有超级管理员能更新
            try{
                $schoolData['uuid'] = Uuid::uuid4()->toString();
                $school =  School::create($schoolData);
                // 学校创建成功之后, 创建一个默认的主校区
                if($school){
                    Campus::create([
                        'school_id'=>$school->id,
                        'last_updated_by'=>$this->currentUser->id,
                        'name'=>'主校区',
                        'description'=>$school->name.'主校区'
                    ]);
                    return $school;
                }else{
                    return false;
                }
            }catch (\Exception $exception){
                return false;
            }
        }
        return false;
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
        else{
            return $this->getSchoolById($idOrUuid);
        }
    }
}
