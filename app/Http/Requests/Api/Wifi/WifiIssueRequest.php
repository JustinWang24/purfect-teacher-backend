<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 19/11/19
 * Time: 11:33 AM
 */
namespace App\Http\Requests\Api\Wifi;

/**
 * Class WifiIssueRequest
 * @package App\Http\Requests\Api\Wifi
 */
class WifiIssueRequest extends \App\Http\Requests\ApiRequest
{
   public $rules = [];

   protected $messages = [
      'uuid.required' => '参数不能为空' ,

      // 添加报修
      'typeone_id.required' => '请选择一级分类' ,
      'typeone_id.integer' => '请选择一级分类' ,
      'typeone_name.required' => '请选择一级分类' ,
      'typetwo_id.required' => '请选择二级分类' ,
      'typetwo_id.integer' => '请选择而级分类' ,
      'typetwo_name.required' => '请选择二级分类' ,
      'issue_name.required' => '请填写联系人' ,
      'issue_mobile.required' => '请填写联系电话' ,
      'addressoneid.required' => '请选择地址' ,
      'addressoneid.integer' => '请选择地址' ,
      'addresstwoid.required' => '请选择地址' ,
      'addresstwoid.integer' => '请选择地址' ,
      'addr_detail.integer' => '请填写地址详情' ,

      // 报修列表
      'page.required' => '分页ID不能为空' ,
      'page.integer' => '分页ID必须为整数' ,

      // 报修详情
      'issueid.required' => '报修ID不能为空' ,
      'issueid.integer' => '报修ID必须为整数' ,

      // 评价单个报修
      'issueid.required' => '参数不能为空' ,
      'issueid.integer' => '参数必须为整数' ,
      'comment_service.required' => '请选择服务态度' ,
      'comment_service.integer' => '请选择服务态度' ,
      'comment_service.between' => '请选择服务态度' ,
      'comment_worders.required' => '请选择工作效率' ,
      'comment_worders.integer' => '请选择工作效率' ,
      'comment_worders.between' => '请选择工作效率' ,
      'comment_efficiency.required' => '请选择满意度' ,
      'comment_efficiency.integer' => '请选择满意度' ,
      'comment_efficiency.between' => '请选择满意度' ,
      'comment_content.required' => '请填写评价内容' ,
   ];

   public function rules ()
   {
      $rules = $this->rules;
      $getActionName = request()->route()->getActionName();

      // 报修类型
      if(stripos($getActionName,'list_category_info'))
      {
         $rules[ 'uuid' ] = 'required'; // uuid
      }

      // 添加报修
      if(stripos($getActionName,'add_issue_info'))
      {
         $rules[ 'uuid' ]         = 'required'; // uuid
         $rules[ 'typeone_id' ]   = 'required|integer'; // 一级分类ID
         $rules[ 'typeone_name' ] = 'required'; // 一级分类名称
         $rules[ 'typetwo_id' ]   = 'required|integer'; // 二级分类ID
         $rules[ 'typetwo_name' ] = 'required'; // 二级分类名称
         $rules[ 'issue_name' ]   = 'required'; // 联系人姓名
         $rules[ 'issue_mobile' ] = 'required'; // 联系人电话
         $rules[ 'addressoneid' ] = 'required|integer'; // 一级地址ID
         $rules[ 'addresstwoid' ] = 'required|integer'; // 二级地址ID
         $rules[ 'addr_detail' ]  = 'required'; // 地址详情
         // $rules[ 'issue_desc' ]   = 'required'; // 备注说明
      }

      // 我的报修列表
      if(stripos($getActionName,'list_my_issue_info'))
      {
         $rules[ 'uuid' ] = 'required'; // uuid
         $rules[ 'page' ] = 'required|integer'; // 分页id
      }

      // 报修详情
      if(stripos($getActionName,'get_my_issue_info'))
      {
         $rules[ 'uuid' ] = 'required'; // uuid
         $rules[ 'issueid' ] = 'required|integer'; // 报修id
      }

      // 评价单个报修信息
      if(stripos($getActionName,'add_issue_comment_info'))
      {
         $rules[ 'uuid' ] = 'required'; // uuid
         $rules[ 'issueid' ] = 'required|integer'; // 报修id
         $rules[ 'comment_service' ] = 'required|integer|between:1,5'; // 服务态度
         $rules[ 'comment_worders' ] = 'required|integer|between:1,5'; // 工作效率
         $rules[ 'comment_efficiency' ] = 'required|integer|between:1,5'; // 满意度
         $rules[ 'comment_content' ] = 'required'; // 评价内容
      }
      return $rules;
   }

   public function messages ()
   {
      return $this->messages;
   }

}
