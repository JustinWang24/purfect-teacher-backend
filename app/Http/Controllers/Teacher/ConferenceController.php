<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2019/10/23
 * Time: 下午3:11
 */
namespace App\Http\Controllers\Teacher;

use App\Dao\Teachers\ConferenceDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConferenceController extends Controller
{
    public function index()
    {
        return view('teacher.conference.index', $this->dataForView);
    }


    public function data(Request $request)
    {
        #判断权限
//        $schoolId = $request->session()->get('school.id');
        $userId = Auth::id();
        $dao = new ConferenceDao();
        $map = ['user_id'=>$userId];
        $list = $dao->getList($map)->toArray();
//        dd($list);
//        $list = [['id'=>1]];

        echo json_encode(['code'=>0,"count" => 1, "data" => $list]);die;
//        dd($userId);

    }

    public function add(Request $request)
    {
        if($request->isMethod('post'))
        {
            $data = $request->post();

        }

        return view('teacher.conference.add', $this->dataForView);
    }


    public function conferenceUser(Request $request)
    {
        $from = $request->post('from');
        $to = $request->post('to');
        $dao = new ConferenceDao();

    }

}