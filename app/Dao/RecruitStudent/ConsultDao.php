<?php
namespace App\Dao\RecruitStudent;

use App\Models\Schools\Consult;

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
     * @return mixed
     */
    public function getConsultPage($schoolId) {
        return Consult::where('school_id',$schoolId)->paginate(10);
    }

    /**
     * 获取咨询详情
     * @param $id
     * @return mixed
     */
    public function getConsultById($id) {
        return Consult::where('id',$id)->first();
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
