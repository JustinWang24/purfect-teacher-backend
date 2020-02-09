<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Api;

use App\Models\Affiche\AfficheVideo;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class AfficheVideoDao extends \App\Dao\Affiche\CommonDao
{
    public function __construct()
    {
    }

    /**
     * Func 添加
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function addAfficheVideoInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = AfficheVideo::create($data)) {
                DB::commit();
                return $obj->id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

}
