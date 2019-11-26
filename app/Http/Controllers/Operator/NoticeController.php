<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notice\NoticeRequest;
use App\Dao\Notice\NoticeDao;

class NoticeController extends Controller
{

    public function index(NoticeRequest $request)
    {
        $schoolId = $request->getSchoolId();

        $dao = new NoticeDao;

        $search = $request->get('search');

        if ($search) {
            $where = ['school_id' => $schoolId, $search];
        } else {
            $where = ['school_id' =>$schoolId];
        }

        $data = $dao->getNoticeBySchoolId($where);
        dd($data->toArray());
        $this->dataForView['data'] = $data;
        return view('school_manager.banner.index', $this->dataForView);
    }


    public function add()
    {

    }


    public function update()
    {

    }

    public function save()
    {

    }

}
