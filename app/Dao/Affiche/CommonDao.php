<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/02/01
 * Time: 9:05 AM
 */
namespace App\Dao\Affiche;

use App\Dao\Users\UserDao;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class CommonDao
{
    public static $limit = 10;
    public static $bcckend_limit = 15;
   //---------------------------------------api定义的静态属性-------------------------------------
   // 状态 状态(0:关闭,1:开启)
   public static $wifiContentsStatusArr = [
      0 => '正常' ,
      1 => '关闭' ,
   ];
    // 组织公告状态 状态(0:关闭,1:开启)
    public static $noticeStatusArr = [
        0 => '关闭' ,
        1 => '正常' ,
    ];
    // 状态 状态(-1:待审核,0:关闭活动,1:审核通过,2:审核未通过)
    public static $afficheStatusArr = [
        -1 => '待审核' ,
        0 => '已关闭' ,
        1 => '审核通过' ,
        2 => '审核未通过' ,
    ];
    // 类型 'text','video','image'
    public static $afficheTypeArr = [
        'text' => '文字' ,
        'video' => '视频' ,
        'image' => '图片'
    ];
    // 评价(1:未评价,2:已评价)
    public static $commentArr = [
        1 => '未评价',
        2 => '已评价',
    ];

    // 组织认证
    // 类型(1:学生会,2:社团)
    public static $groupTypeIdArr = [
        1 => '学生会',
        2 => '社团',
    ];
    // 状态(-1:待审核,1:审核通过,2:审核驳回)
    public static $groupStatusArr = [
        -1 => '待审核',
        1 => '通过',
        2 => '未通过',
    ];
    public static $GroupJoinTypeidArr = [
        1 => '团长',
        4 => '普通用户'
    ];

    //---------------------------------------manger定义的静态属性----------------------------------

   // 是否评价(1:未评价,2:已评价)
   public static $manageCommentArr = [
      1 => '未评价',
      2 => '已评价',
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

    /**
     * Func 获取用户信息
     *
     * @param $user_id 用户ID
     *
     * return array
     */
    public function userInfo($user_id = 0)
    {
        $userObj = new UserDao();
        $userInfo = $userObj->getUserById($user_id);

        // TODO.... 下面没写完.....
        $data['user_id'] = $userInfo->id; // 用户id
        $data['user_sex'] = '男'; // 性别
        $data['user_signture'] = $userInfo->user_signture; // 签名
        $data['user_pics'] = '/assets/img/dp.jpg'; // 头像
        $data['user_nickname'] = $userInfo->user_signture; // 用户名
        $data['school_name'] = '礼县职业中等专业学校'; // 学校名称
        $data['user_fans_number'] = 10; // 粉丝数量
        $data['user_focus_number'] = 20; // 关注数量
        $data['user_praise_number'] = 30; // 点赞数量

         return $data;
    }

    /**
     * Func 获取用户粉丝数关注数点赞数
     *
     * @param $user_id 用户ID
     *
     * return array
     */
    public function getFansOrFocusOrPraiseNumber($user_id = 0)
    {
        // TODO........
        $data['user_fans_number'] = 10; // 粉丝数量
        $data['user_focus_number'] = 20; // 关注数量
        $data['user_praise_number'] = 30; // 点赞数量
        return $data;
    }

    /**
     * Func 获取社群详情背景图
     *
     * @param $user_id 用户ID
     *
     * return array
     */
    public function getUserColorInfo($user_id = 0)
    {
        // TODO.... 下面没写完.....
        $data['user_color_title'] = '';  // 名称
        $data['user_color_small'] = '';  // 小图
        $data['user_color_big'] = '';  // 大图

        return $data;
    }

    /**
     * Func 获取认证信息
     *
     * @param $user_id 用户ID
     *
     * return array
     */
    public function getAuthIno($user_id = 0)
    {
        // TODO.... 下面没写完.....
        $data['user_username'] = '109991';  //编号
        $data['user_usercode'] = '张三';  //姓名
        $data['user_cardno'] = '18127192837382893'; //身份证号码

        return $data;
    }

   /**
    * Func 树接口
    *
    * @param $list
    *
    * @return array
    */
   public static function cateTree( $list , $idName = 'id' , $pidName = 'pid' )
   {
      $items = [ ];
      foreach ( $list as $k => $v )
      {
         $items[ $v[ $idName ] ] = $v;
      }
      $tree = [ ];
      foreach ( $items as $item )
      {
         if ( isset( $items[ $item[ $pidName ] ] ) )
         {
            $items[ $item[ $pidName ] ][ 'child' ][] = &$items[ $item[ $idName ] ];
         } else {
            $tree[] = &$items[ $item[ $idName ] ];
         }
      }
      return $tree;
   }

    /*计算内容的发送的时间*/
    function transTime( $ustime )
    {
        $ytime     = date( 'Y-m-d H:i' , $ustime );
        $rtime     = date( 'n月j日 H:i' , $ustime );
        $htime     = date( 'H:i' , $ustime );
        $time      = time() - $ustime;
        $todaytime = strtotime( 'today' );
        $time1     = time() - $todaytime;

        if ( $time < 60 ) {
            $str = '刚刚';
        }
        else if ( $time < 60 * 60 ) {
            $min = floor( $time / 60 );
            $str = $min . '分钟前';
        }
        else if ( $time < $time1 ) {
            $str = '今天' . $htime;
        }
        else {
            $str = $rtime;
        }

        return $str;
    }

    /*计算内容的发送的时间*/
    function transTime1( $ustime )
    {
        $rtime = date ( 'Y-m-d' , $ustime );
        $time  = time () - $ustime;

        // 1小时内
        if ( $time < 3600 )
        {
            $str = '刚刚';
        } else if ( $time >= 3600 && $time <= 86400 ) // ** 小时前
        {
            $hours = floor( $time / 3600 );
            $str = $hours . '小时前';
        } else if ( $time >= 86400 && $time <= 86400 * 2 )// 1天前
        {
            $str = '1天前';
        } else {
            $str = $rtime;
        }
        return $str;
    }

    /*计算内容的发送的时间*/
    function transTime2( $ustime )
    {
        $rtime = date( 'n月j日', $ustime );
        $time  = date('Y-m-d', time());
        $time1 = date('Y-m-d', $ustime);
        if ( $time == $time1 ) {
            $str = '今天 '; #空格不要去掉
        }
        else {
            $str = $rtime;
        }
        return $str;
    }

    /*计算内容的发送的时间*/
    function transTime3( $ustime )
    {
        $time      = time() - $ustime;
        if ( $time < 60 * 60 ) {
            $min = floor( $time / 60 );
            $str = $min . '分钟前';
        }
        else if ( $time < 60 * 60 * 24 ) {
            $hour = floor( $time / 60 / 60 );
            $str = $hour . '小时前';
        }
        else {
            $str = date( 'm-d' , $ustime );
        }
        return $str;
    }

}
