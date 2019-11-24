<?php

namespace App\Http\Controllers\Operator\RecruitStudent;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\RegistrationInformationRequest;
use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Utils\JsonBuilder;
use Carbon\Carbon;

class RegistrationInformatics extends Controller
{

    /**
     * 报名列表
     * @param RegistrationInformationRequest $request
     * @return mixed
     */
    public function index(RegistrationInformationRequest $request)
    {
        $dao  = new  RegistrationInformaticsDao;

        $time   = $request->get('time');
        $major  = $request->get('major');
        $name   = $request->get('name');
        $status = $request->get('status');
        $sort  = $request->get('sort');
        $where = [];
        if ($time) {
            $where = [['created_at', '>', $time['start_time']], ['created_at', '<', $time['end_time']]];
        }

        if ($major) {
            $where[] = ['major_id', '=', $major];
        }

        if ($name) {
            $where[] = ['name', '=', $name];
        }

        if($status) {
            $where[] = ['status', '=', $status];
        }

        $field = ['id', 'user_id', 'name', 'school_id',
            'major_id', 'relocation_allowed', 'status',
        ];

        $data = $dao->getRegistrationInformatics($field, $where, $sort);

        return JsonBuilder::Success($data);
    }


    /**
     * 报名详情
     * @param RegistrationInformationRequest $request
     * @return string
     */
    public function details(RegistrationInformationRequest $request)
    {
        $id = $request->get('id');

        $dao  = new  RegistrationInformaticsDao;

        $field = ['id', 'user_id', 'name', 'school_id',
            'major_id', 'relocation_allowed', 'status',
            'note', 'created_at'
        ];

        $data = $dao->getOneDataInfoById($field, $id);

        return JsonBuilder::Success($data);
    }

    /**
     * 报名审核
     * @param RegistrationInformationRequest $request
     * @return string
     */
    public function examine(RegistrationInformationRequest $request)
    {
         $id = $request->get('id');
         $data = $request->get('data');

         $dao  = new  RegistrationInformaticsDao;

         $result = $dao->update($id, $data);
         if ($result) {
            return JsonBuilder::Success('更新成功');
         } else {
            return JsonBuilder::Error('更新失败');
         }
    }



}
