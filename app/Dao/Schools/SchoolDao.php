<?php
namespace App\Dao\Schools;

use App\Models\Schools\Organization;
use App\Models\Schools\SchoolConfiguration;
use App\User;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\Models\Schools\Campus;

class SchoolDao
{
    private $currentUser;

    /**
     * SchoolDao constructor.
     * @param User|null $user
     */
    public function __construct($user = null)
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
            DB::beginTransaction();
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
                    // 创建学校的基本配置信息
                    $this->createDefaultConfig($school);
                    // 创建学校的最基本的组织
                    $this->createRootOrganization($school);
                    DB::commit();
                    return $school;
                }else{
                    DB::rollBack();
                    return false;
                }
            }catch (\Exception $exception){
                DB::rollBack();
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

    /**
     * 更新学校的配置信息. 如果配置信息不存在, 则创建它
     * @param $configuration
     * @param array $ec1
     * @param array $ec2
     * @param School|int $school
     * @return mixed
     */
    public function updateConfiguration($configuration, $ec1, $ec2, $school){
        $configuration['apply_elective_course_from_1'] = SchoolConfiguration::CreateMockEcDate($ec1,'from');
        $configuration['apply_elective_course_to_1'] = SchoolConfiguration::CreateMockEcDate($ec1,'to');
        $configuration['apply_elective_course_from_2'] = SchoolConfiguration::CreateMockEcDate($ec2,'from');
        $configuration['apply_elective_course_to_2'] = SchoolConfiguration::CreateMockEcDate($ec2,'to');

        if(isset($school->configuration->id)){
            return SchoolConfiguration::where('school_id',$school->id ?? $school)->update($configuration);
        }
        else{
            $configuration['school_id'] = $school->id ?? $school;
            return SchoolConfiguration::create($configuration);
        }
    }

    /**
     * 创建一个学校的默认配置项
     * @param School|int $school
     * @return SchoolConfiguration
     */
    public function createDefaultConfig($school){
        return (new SchoolConfiguration())->createDefaultConfig($school);
    }

    /**
     * 创建学校的根组织机构
     * @param $school
     * @return Organization
     */
    public function createRootOrganization($school){
        $data = [
            'school_id'=>$school->id??$school,
            'name'=>'学校组织机构',
            'level'=>Organization::ROOT,
            'parent_id'=>0,
        ];

        return Organization::create($data);
    }

    /**
     * 根据名字查询学校
     * @param $schoolName
     * @return
     */
    public function getSchoolByName($schoolName)
    {
        return School::where('name',$schoolName)->first();
    }
}
