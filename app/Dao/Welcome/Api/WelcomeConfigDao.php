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
     * @param $school_id 学校id
     * @param $fieldArr 字段信息
     *
     * @return Collection
     */
    public function getWelcomeConfigOneInfo( $school_id , $fieldArr = ['*'] )
    {
        return WelcomeConfig::where('school_id', $school_id)
            ->where('status', 1)->select($fieldArr)->first();
    }

    /**
     * Func 获取报到流程
     *
     * @param $school_id 学校id
     *
     * @return Collection
     */
    public function getWelcomeConfigStepListInfo($school_id)
    {
        // 检索条件
        $condition[] = ['a.school_id', '=', (Int)$school_id];
        $condition[] = ['a.status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'a.school_id', 'b.gname',
            'a.steps_id', 'b.name', 'b.letter', 'b.icon'
        ];

        $data = WelcomeConfig::from('welcome_config_steps as a')
            ->where($condition)->select($fieldArr)
            ->join('welcome_steps as b', 'a.steps_id', '=', 'b.stepsid')
            ->orderBy('a.sort', 'asc')
            ->get();

        return !empty($data->toArray()) ? $data->toArray() : [];

    }
}
