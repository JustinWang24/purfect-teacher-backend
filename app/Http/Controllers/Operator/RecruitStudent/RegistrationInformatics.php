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

        $dao = new  RegistrationInformaticsDao;

        $field = ['name', 'gender', 'birth_time', 'nation',
                  'political', 'source_place', 'mobile',
                  'parent_name', 'parent_mobile', 'whether_adjust'
        ];

        $data = $dao->getRegistrationInformatics($field);
        if ($data) {

            foreach ($data as $key  => $val) {
            $date = Carbon::parse($val['birth_time']);
            dd($date->isoFormat('Y.M.D'));
            }
        }
        return JsonBuilder::Success($data);
    }

}
