<?php

namespace App\Dao\OA;

use App\Models\OA\InternalMessage;
use App\Models\OA\InternalMessageFile;
use Illuminate\Support\Facades\DB;

class InternalMessageDao
{

    public function create($data, $files)
    {
        DB::beginTransaction();
        try{
            if ($data['is_relay'] == 1) {

            }

            $message = InternalMessage::create($data);

            #处理收件人数据
            $collId = explode(',', $data['collect_user_id']);
            $collData = [];
            foreach ($collId as $key => $value) {
                $collData[$key]['user_id']           = $data['user_id'];
                $collData[$key]['collect_user_id']   = $value;
                $collData[$key]['collect_user_name'] = $data['collect_user_name'];
                $collData[$key]['title']             = $data['title'];
                $collData[$key]['content']           = $data['content'];
                $collData[$key]['type']              = 1;
                $collData[$key]['is_relay']          = $data['is_relay'];
                dd($collData);
                InternalMessage::create($collData);
            }

            if ($files) {
                foreach ($files as $key => $val) {
                    $val['message_id'] = $message->id;
                    InternalMessageFile::create($val);
                }
            }
            DB::commit();
            $result = true;
        }catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            $result = false;
        }

        return $result;
    }

}
