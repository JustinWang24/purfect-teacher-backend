<?php

namespace App\Dao\Forum;

use App\Models\Forum\Forum;
use App\Models\Forum\ForumImage;
use Illuminate\Support\Facades\DB;

class ForumDao
{

    /**
     * @param $data
     * @param $resources
     * @return bool
     */
    public function add($data, $resources)
    {
        DB::beginTransaction();
        try{
            $dataResult = Forum::create($data);
            foreach ($resources as $key => $val) {
                $val['forum_id'] = $dataResult->id;
                ForumImage::create($val);
            }
            DB::commit();
            $result = true;
        }catch (\Exception $e) {
            DB::rollBack();
            $result = false;
        }

        return $result;
    }

}
