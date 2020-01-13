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
                $collData['user_id']           = $data['user_id'];
                $collData['collect_user_id']   = $value;
                $collData['collect_user_name'] = $data['collect_user_name'];
                $collData['title']             = $data['title'];
                $collData['content']           = $data['content'];
                $collData['type']              = 1;
                $collData['is_relay']          = $data['is_relay'];
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
