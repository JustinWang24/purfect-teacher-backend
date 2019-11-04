<?php


namespace App\Dao\RecruitStudent;

use App\Models\RecruitStudent\RegistrationInformatics;

class RegistrationInformaticsDao
{

    /**
     * 报名列表
     * @param $field
     * @param array $where
     * @param $order
     * @return object
     */
    public function getRegistrationInformatics($field, $where = [], $order)
    {
        $data = RegistrationInformatics::select($field)->where($where)
                ->with([
                'studentProfile' => function($query) {
                   $query->select('user_id', 'gender', 'nation_name', 'political_name',
                    'source_place', 'parent_name', 'parent_mobile', 'birthday');
                },
                'school' => function($query) {
                    $query->select('id', 'name');
                },
                'major' => function($query) {
                    $query->select('id', 'name');
                }])
                ->orderBy('created_at', 'desc')
                ->orderBy('relocation_allowed', $order)
                ->paginate(RegistrationInformatics::PAGE_NUMBER);

        return $data;
    }

    /**
     * 获取一条报名详情
     * @param $field
     * @param $id
     * @return object
     */
    public function getOneDataInfoById($field, $id)
    {
        $data = RegistrationInformatics::select($field)->where('id', $id)
                ->with([
                'user' => function($query) {
                    $query->select('id', 'mobile', 'email');
                },
                'studentProfile' => function($query) {
                   $query->select('user_id', 'gender', 'nation_name', 'political_name',
                       'source_place', 'parent_name', 'parent_mobile', 'birthday', 'id_number',
                       'country', 'qq', 'wx', 'state', 'city', 'area', 'examination_score',
                       'detailed_address'
                   );
                },
                'school' => function($query) {
                    $query->select('id', 'name');
                },
                'major' => function($query) {
                    $query->select('id', 'name');
                }])
                ->first();

        return $data;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return RegistrationInformatics::where('id', $id)->update($data);
    }

    /**
     * 根据userId获取报名信息
     * @param $userId
     * @return mixed
     */
    public function getInformaticsByUserId($userId)
    {
        return RegistrationInformatics::where('user_id', $userId)->get();
    }



}
