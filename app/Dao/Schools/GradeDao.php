<?php
namespace App\Dao\Schools;
use App\Models\Schools\GradeManager;
use App\User;
use App\Models\Schools\Grade;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use App\Utils\JsonBuilder;

class GradeDao
{
    private $currentUser;

    /**
     * GradeDao constructor.
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
        return Grade::select(['id','name'])
            ->where('school_id',$schoolId)->where('name','like',$name.'%')->get();
    }

    /**
     * @param $id
     * @return Grade
     */
    public function getGradeById($id){
        return Grade::find($id);
    }

    /**
     * @param $id
     * @return Grade
     */
    public function getBySchool($id){
        return Grade::where('school_id',$id)->paginate();
    }

    /**
     * @param $id
     * @param $year
     * @return Grade
     */
    public function getBySchoolAndYear($id, $year){
        return Grade::where('school_id',$id)->where('year',$year)->paginate();
    }

    public function getBySchoolAndYearForApp($id, $year, $withoutYear = false){
        if($withoutYear){
            return Grade::select(['id','name'])->where('school_id',$id)->where('year',$year)->get();
        }
        return Grade::select(['id','name','year'])->where('school_id',$id)->where('year',$year)->get();
    }

    /**
     * @param $id
     * @return Collection
     */
    public function getAllBySchool($id){
        return Grade::select('id','name')->where('school_id',$id)->get();
    }

    /**
     * 根据给定的专业和年份获取班级
     * @param $majorId
     * @param $year
     * @param $field
     * @return Collection
     */
    public function getGradesByMajorAndYear($majorId, $year,$field='*'){
        return Grade::where('major_id',$majorId)
            ->where('year',$year)
            ->orderBy('name','asc')
            ->select($field)
            ->get();
    }

    /**
     * 创建班级
     * @param $data
     * @return Grade
     */
    public function createGrade($data){
        $data['last_updated_by'] = $this->currentUser->id;
        return Grade::create($data);
    }

    /**
     * 更新班级的数据
     * @param $data
     * @param null $where
     * @param null $whereValue
     * @return mixed
     */
    public function updateGrade($data, $where = null, $whereValue = null){
        $id = $data['id'];
        $data['last_updated_by'] = $this->currentUser->id;
        unset($data['id']);
        if($whereValue && $where){
            return Grade::where($where,$whereValue)->update($data);
        }
        return Grade::where('id',$id)->update($data);
    }

    /**
     * 根据名字和专业获取班级
     * @param $name
     * @param $schoolId
     * @param $majorId
     * @return mixed
     */
    public function getByName($name, $schoolId, $majorId, $year){
        return Grade::where('school_id',$schoolId)->where('major_id',$majorId)->
                      where('year',$year)->where('name',$name)->first();
    }

    /**
     * 设置班主任
     * @param $data
     * @return MessageBag
     */
    public function setAdviser($data){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);

        try{
            if(empty($data['id'])){
                // 创建
                GradeManager::create($data);
            }
            else{
                GradeManager::where('id',$data['id'])->update($data);
            }
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }
        catch (\Exception $exception){
            $bag->setMessage($exception->getMessage());
        }
        return $bag;
    }

    /**
     * 获取多个班级
     * @param $ids
     * @return Grade
     */
    public function getGrades($ids)
    {
        return Grade::whereIn('id', $ids)->get();
    }
}
