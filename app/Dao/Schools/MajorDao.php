<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 9:05 AM
 */

namespace App\Dao\Schools;
use App\User;
use App\Models\Schools\Major;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Database\Eloquent\Collection;

class MajorDao
{
    private $currentUser;

    /**
     * MajorDao constructor.
     * @param User|null $user
     */
    public function __construct($user = null)
    {
        $this->currentUser = $user;
    }

    /**
     * @param $name
     * @param $schoolId
     * @return Collection
     */
    public function searchByName($name, $schoolId){
        return Major::select(['id','name'])
            ->where('school_id',$schoolId)->where('name','like',$name.'%')->get();
    }

    /**
     * 根据给定的 campus 或 id 获取包含的学院
     * @param $department
     * @param $field
     * @return Collection
     */
    public function getByDepartment($department,$field='*'){
        if(is_object($department)){
            $department = $department->id;
        }
        return Major::where('department_id',$department)->select($field)->get();
    }

    /**
     * 根据给定的 campus 或 id 获取包含的学院
     * @param $schoolId
     * @param $field
     * @return Collection
     */
    public function getBySchool($schoolId,$field='*'){
        return Major::where('school_id',$schoolId)->select($field)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param $id
     * @return Major|null
     */
    public function getMajorById($id){
        return Major::find($id);
    }

    /**
     * @param $schoolId
     * @param bool $openedOnly : 只加载公开的专业 - 不再使用了
     * @param bool $hotOnly: 只加载热门的 - 不再使用了
     * @param bool $simple
     * @return Collection
     */
    public function getMajorsBySchool($schoolId, $openedOnly = false, $hotOnly = false, $simple = true){
        $where = [
            ['school_id','=',$schoolId]
        ];

        if($simple)
            return Major::select(['id','name'])->where($where)->orderBy('name','asc')->get();
        return Major::where($where)->orderBy('name','asc')->get();
    }

    /**
     * 只加载开放报名的专业列表
     * @param $schoolId
     * @param $hotOnly : 是否只加载热门
     * @return array
     */
    public function getOpenedMajorsBySchool($schoolId, $hotOnly = false){
        $majors = $this->getMajorsBySchool($schoolId, true, $hotOnly, false);
        $result = [];
        foreach ($majors as $major) {
            /**
             * @var Major $major
             */
            $result[] = [
                'id'=>$major->id,
                'institute'=>$major->institute->name??'',
                'department'=>$major->department->name,
                'campus'=>$major->campus->name??'',
                'name'=>$major->name,
                'period'=>$major->period, // 几年制
                'description'=>$major->description,
            ];
        }

        return $result;
    }

    /**
     * 创建新的专业
     * @param $majorData
     * @return Major
     */
    public function createMajor($majorData){
        $majorData['last_updated_by'] = $this->currentUser->id;
        return Major::create($majorData);
    }

    /**
     * 更新专业的数据
     * @param $majorData
     * @return Major
     */
    public function updateMajor($majorData){
        $id = $majorData['id'];
        $majorData['last_updated_by'] = $this->currentUser->id;
        unset($majorData['id']);
        return Major::where('id',$id)->update($majorData);
    }
    /**
     * 分页获列表
     * @param $map
     * @param string $field
     * @return mixed
     */
    public function getMajorPage($map,$field='*') {
        return Major::where($map)->select($field)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE_QUICK_SEARCH);
    }


    /**
     * 获取校区下的专业
     * @param int $campusId 校区ID
     * @param bool $simple
     * @return mixed
     */
    public function getMajorsByCampusId($campusId,$simple = true) {
        $field = '*';
        if($simple) {
            $field = ['id','name'];
        }

        return Major::where('campus_id',$campusId)->select($field)->get();
    }

    /**
     * 根据名字和院系获取专业
     * @param $name
     * @param $schoolId
     * @return mixed
     */
    public function getByName($name, $schoolId, $instituteId, $departmentId){
        return Major::where('school_id',$schoolId)->where('institute_id',$instituteId)->
                      where('department_id',$departmentId)->where('name', $name)->first();
    }

    /**
     * 根据学校ID和专业代码获取专业
     * @param $code
     * @param $schoolId
     * @return mixed
     */
    public function getMajorByCategoryCode($code, $schoolId)
    {
        $map = [
            'category_code' => $code,
            'school_id' => $schoolId
        ];
        return Major::where($map)->first();
    }

    /**
     * 根据系ID获取专业
     * @param $departmentId
     * @return mixed
     */
    public function getMajorByDepartmentId($departmentId)
    {
        return Major::where('department_id', $departmentId)->get();
    }
}
