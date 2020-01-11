<?php
namespace App\Dao\RecruitStudent;

use App\Models\Schools\Consult;
use Illuminate\Database\Eloquent\Collection;

class ConsultDao
{

    private $currentUser;

    /**
     * ConsultDao constructor.
     * @param null $user
     */
    public function __construct($user = null)
    {
        $this->currentUser = $user;
    }


    /**
     * 保存咨询信息
     * @param $data
     * @return mixed
     */
    public function saveConsult($data) {

        $data['last_updated_by'] = $this->currentUser->id;
        if(!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $result = Consult::where('id',$id)->update($data);
        } else {

            $result = Consult::create($data);
        }
        return $result;
    }


    /**
     * 获取咨询列表
     * @param $schoolId
     * @return Collection
     */
    public function getConsultPage($schoolId) {
        return Consult::where('school_id',$schoolId)->paginate(10);
    }

    /**
     * 获取咨询详情
     * @param $id
     * @param $simple
     * @return Collection
     */
    public function getConsultById($id, $simple = false) {
        if($simple){
            return Consult::select(['question','answer'])->where('school_id',$id)->get();
        }
        return Consult::where('id',$id)->first();
    }

    /**
     * 获取咨询详情
     * @param $id
     * @param $simple
     * @return Collection
     */
    public function getConsultsBySchool($id, $simple = false) {
        if($simple){
            return Consult::select(['question','answer'])->where('school_id',$id)->get();
        }
        return Consult::where('school_id',$id)->get();
    }

    /**
     * 软删除
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return Consult::where('id', $id)->delete();
    }
}
