<?php

namespace App\Http\Controllers;

use App\Utils\JsonBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	// APP分页条数
	public static $api_wifi_page_limit = 10;

   // 后台分页条数
   public static $manger_wifi_page_limit = 10;

    /**
     * 用来承载View模板数据的container
     * @var array
     */
    public $dataForView = [
        'pageTitle'=>null,          // 当前的页标题
        'currentMenu'=>null,        // 当前的被点选的菜单项
        'footer'=>null,             // 页脚的Block
        'the_referer'=>null,        // 跟踪客户的referer
        'autoThumbnail'=>true,      // 是否自动生成面包屑部分
        'needChart'=>false,         // 是否前端需要 Chart
    ];
	
  /**
   * Func 获取post参数信息
   * @param $postArr 提交参数
   * @param $fieldArr 过来参数
   * return Array
   */
  protected static function getPostParamInfo($postArr=[],$fieldArr=[])
  {
	 $data = [];
	 if ( ! $fieldArr ) return $data;
	 foreach ( $postArr as $key => $val )
	 {
		if ( in_array ( $key , $fieldArr ) )
		{
		   $data[ $key ] = trim ( $val );
		}
	 }
	 return $data;
  }

   /**
    * Func 获取用户和用户所在的学校信息
    * @param $uuid UUID
    *  return true|array
    */
   protected static function authUserInfo ( $uuid = null )
   {
      if ( ! $uuid ) return JsonBuilder::Error ( '参数错误' );

      // TODO....获取用户和学校信息，如果未查询到返回错误信息。

      return [ 'user_id' => 1 , 'school_id' => 1 , 'campus_id' => 1 , 'mobile' => '18601216091' , 'money' => 8000 ];
   }

}
