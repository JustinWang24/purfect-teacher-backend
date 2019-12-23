<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/12/19
 * Time: 9:46 PM
 */

namespace App\Http\Controllers\Api\OA;


use App\Http\Controllers\Controller;
use App\Models\OA\Visitor;
use App\Utils\JsonBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeachersController extends Controller
{

    public function add_visitor(Request $request){
        $user = $request->user();
        $visitorData = $request->get('visitor');
        $visitorData['invited_by'] = $user->id;
        $visitorData['status'] = Visitor::NOT_VISITED;
        $visitorData['scheduled_at'] = Carbon::createFromFormat('Y-m-d H:i:s',$visitorData['scheduled_at']['date'].' '.$visitorData['scheduled_at']['time']);
        $visitorData['arrived_at'] = Carbon::createFromFormat('Y-m-d H:i:s',$visitorData['arrived_at']['date'].' '.$visitorData['arrived_at']['time']);
        // 处理时间
        $visitor = Visitor::create($visitorData);
        return JsonBuilder::Success(['visitor'=>$visitor]);
    }
}