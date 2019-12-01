<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/12/19
 * Time: 8:57 PM
 */

namespace App\Dao\Performance;


use App\Models\Teachers\Performance\TeacherPerformance;
use App\Models\Teachers\Performance\TeacherPerformanceItem;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class TeacherPerformanceDao
{
    private $schoolId;

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    /**
     * @param $performance
     * @param $items
     * @return IMessageBag
     */
    public function create($performance, $items, $approvedBy){
        DB::beginTransaction();
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        try{
            $performance['school_id'] = $this->schoolId;
            $performance['approved_by'] = $approvedBy->id ?? $approvedBy;
            $p = TeacherPerformance::create($performance);
            if($p){
                foreach ($items as $item){
                    $item['teacher_performance_id'] = $p->id;
                    TeacherPerformanceItem::create($item);
                }
                DB::commit();
                $bag->setCode(JsonBuilder::CODE_SUCCESS);
                $bag->setData($p);
            }
        }
        catch (\Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
        }
        return $bag;
    }
}