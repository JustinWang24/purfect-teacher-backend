<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Affiche\Api;

use App\Dao\Users\UserDao;
use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;

use App\Models\Affiche\Affiche;
use App\Models\Affiche\AfficheStick;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Builder\UuidBuilderInterface;

class IndexDao extends \App\Dao\Affiche\CommonDao
{
    public function __construct()
    {
    }

    /**
     * Func 获取首页推荐的数据
     *
     * @param['school_id']  学校ID,0表示未登录
     * @param['stick_posit']  位置(1:首页推荐,2:首页本校)
     * @param['page']  分页id
     *
     * @return array
     */
    public function getStickListInfo($school_id = 0, $stick_posit = 1, $page = 1 )
    {
        // 检索条件
        $condition[] = ['a.school_id', '=', (Int)$school_id];
        $condition[] = ['a.stick_posit', '=', (Int)$stick_posit];
        $condition[] = ['a.status', '=', 1];
        $condition[] = ['b.status', '=', 1];

        // 获取的字段
        $fieldArr = [
            'b.icheid', 'b.user_id','b.school_id', 'b.iche_type', 'b.iche_title',
            'b.iche_content', 'b.iche_view_num', 'b.iche_share_num',
            'b.iche_praise_num', 'b.iche_comment_num', 'b.created_at'
        ];

        $data = AfficheStick::from('affiche_sticks as a')
            ->where($condition)->select($fieldArr)
            ->join('affiches as b', 'a.stick_mixid', '=', 'b.icheid')
            ->orderBy('a.stick_order', 'desc')
            ->orderBy('a.stickid', 'desc')
            ->offset($this->offset($page))
            ->limit(self::$limit)
            ->get();

        return !empty($data->toArray()) ? $data->toArray() : [];
    }


}
