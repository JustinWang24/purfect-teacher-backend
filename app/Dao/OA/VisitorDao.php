<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/11/19
 * Time: 4:33 PM
 */

namespace App\Dao\OA;


use App\Models\OA\Visitor;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Collection;

class VisitorDao
{
    public function __construct()
    {
    }

    /**
     * 根据学校获取访客列表
     * @param $schoolId
     * @return Collection
     */
    public function getVisitorsBySchoolId($schoolId){
        return Visitor::where('school_id',$schoolId)
            ->orderBy('id','desc')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    public function getTodayVisitorsBySchoolId($schoolId){
        return Visitor::where('school_id',$schoolId)
            ->orderBy('id','desc')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param $schoolId
     * @param $date
     * @return Collection
     */
    public function getTodayVisitorsBySchoolIdForApp($schoolId, $date){
        return Visitor::where('school_id',$schoolId)
            ->whereDate('scheduled_at',$date)
            ->orderBy('id','desc')->get();
    }
}