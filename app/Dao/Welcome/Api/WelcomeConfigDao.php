<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Welcome\Api;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;

use App\Models\Welcome\Api\WelcomeConfig;

class WelcomeConfigDao
{
    /**
     * Func 获取学校配置信息
     *
     * @param $campusId 校区ID
     * @param $fieldArr 字段信息
     *
     * @return Collection
     */
    public function getWelcomeConfigOneInfo( $campusId , $fieldArr = ['*'] )
    {
        return WelcomeConfig::where('campus_id', $campusId)
            ->where('status', 1)->select($fieldArr)->first();
    }

}
