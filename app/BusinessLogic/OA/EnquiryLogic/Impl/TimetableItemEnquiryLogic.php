<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/10/19
 * Time: 8:25 AM
 */

namespace App\BusinessLogic\OA\EnquiryLogic\Impl;
use App\BusinessLogic\OA\EnquiryLogic\GeneralEnquiryLogic;
use App\Models\Timetable\TimetableItemEnquiry;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;
use App\Dao\Misc\EnquiryDao;

class TimetableItemEnquiryLogic extends GeneralEnquiryLogic
{
    /**
     * @param $data
     * @param $user
     * @return IMessageBag
     */
    public function create($data, $user)
    {
        $created = false;
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();
        // 先生成enquiry
        $dao = new EnquiryDao();
        $enquiry = $dao->createEnquiry($data, $user);
        if($enquiry){
            // 根据 timetable item 创建关联关系
            $created = TimetableItemEnquiry::create([
                'enquiry_id'=>$enquiry->id,
                'timetable_item_id'=>$data['data']['id'],
                'scheduled_at'=>$enquiry->getScheduledAt(),
                'end_at'=>$enquiry->getEndedAt()
            ]);
            if($created){
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($enquiry);
            }else{
                $messageBag->setMessage('无法根据 课程表项 创建关联关系');
            }
        }else{
            $messageBag->setMessage('创建新的请求记录失败了');
        }

        if($created){
            DB::rollBack();
        }
        // 返回结果
        return $messageBag;
    }
}