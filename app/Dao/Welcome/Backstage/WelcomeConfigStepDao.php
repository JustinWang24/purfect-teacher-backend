<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Welcome\Backstage;

use App\Models\Welcome\WelcomeConfigStep;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class WelcomeConfigStepDao extends \App\Dao\Welcome\CommonDao
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
    public function addWelcomeConfigStepInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($obj = WelcomeConfigStep::create($data)) {
                DB::commit();
                $id = $obj->id;
                $this->editWelcomeConfigStepInfo(['sort' => $id], $id);
                return $id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Func 修改
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function editWelcomeConfigStepInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (WelcomeConfigStep::where('flowid',$id)->update($data)) {
                DB::commit();
                return $id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Func 删除单个路程信息
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function deleteWelcomeConfigStepInfo($id = 0)
    {
        if (!intval($id))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (WelcomeConfigStep::where('flowid',$id)->delete()) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Func 获取学校是否配置迎新
     *
     * @param['school_id']  学校id
     *
     * @return array
     */
    public function getWelcomeConfigStepOneInfo($school_id = 0, $steps_id = 0)
    {
        if (!intval($school_id) || !intval($steps_id)) return [];

        // 检索条件
        $condition[] = ['school_id', '=', $school_id];
        $condition[] = ['steps_id', '=', $steps_id];

        $data = WelcomeConfigStep::where($condition)->first(['*']);

        return !empty($data) ? $data->toArray() : [];
    }

    /**
     * Func 获取迎新流程列表
     *
     * @param['school_id']  学校ID,0表示未登录
     * @param['stick_posit']  位置(1:首页推荐,2:首页本校)
     * @param['page']  分页id
     *
     * @return array
     */
    public function getWelcomeConfigStepListInfo($school_id = 0)
    {
        // 检索条件
        $condition[] = ['a.school_id', '=', (Int)$school_id];
        $condition[] = ['a.status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'a.flowid', 'a.sort', 'a.school_id', 'b.gname', 'b.pics',
            'a.steps_id', 'b.name', 'b.letter', 'b.icon'
        ];

        $data = WelcomeConfigStep::from('welcome_config_steps as a')
            ->where($condition)->select($fieldArr)
            ->join('welcome_steps as b', 'a.steps_id', '=', 'b.stepsid')
            ->orderBy('a.sort', 'asc')
            ->get();

        return !empty($data->toArray()) ? $data->toArray() : [];
    }
}
