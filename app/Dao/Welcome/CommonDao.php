<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 21/10/19
 * Time: 9:05 AM
 */

namespace App\Dao\Welcome;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CommonDao
{
    public static $limit = 10;

    // 状态(0:关闭，2:app个人资料完善中，1:报到中(未交费)，3:已报到(已缴费))
    public static $reportStatusArr = [
        1 => '报到中',
        2 => '待报到',
        3 => '已报到',
    ];

    // 照片名称
    public static $reportPicsArr = [
        'photo' => '一寸照片',
        'card_front' => '身份证正面',
        'card_reverse' => '身份证反面'
    ];

    // 迎新提交资料/缴费项/领取资料
    // 类型(1:学费,2:书费,3:住宿费,4:生活用品,5:军训服装,10:报到函,11:党团文件,12:录取通知书)
    public static $reportProjectArr = [
        // 提交资料
        1 => [
            10 => '报到函',
            11 => '党团文件',
            12 => '录取通知书',
        ],
        // 缴费和领取项
        2 => [
            1 => '学费',
            2 => '书费',
            3 => '住宿费',
            4 => '生活用品',
            5 => '军训服装',
        ],
    ];

    // 支付方式(1: 微信,2: 支付宝,3: 其他)
    public static $paymentArr = [
        1 => '微信',
        2 => '支付宝',
        3 => '其他',
    ];

    // 迎新缴费项目
    public static $projectArr = [
        'photo' => '一寸照片',
        'card_front' => '身份证正面',
        'card_reverse' => '身份证反面'
    ];
   //--------------------------------------public 公共方法——--------------------------------------
    /**
     * Func 获取分页偏移量
     *
     * @param $page 分页ID
     *
     * return Int
     */
    public function offset($page = 1 , $limit = 0)
    {
        return (max(intval($page),1) - 1) * ($limit?$limit:self::$limit);
    }

}
