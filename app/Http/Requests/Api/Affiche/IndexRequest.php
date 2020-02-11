<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Requests\Api\Affiche;

/**
 * Class IndexRequest
 */
class IndexRequest extends \App\Http\Requests\ApiRequest
{
   public $rules = [];

   protected $messages = [
      //'uuid.required' => '参数不能为空' ,
   ];

   public function rules ()
   {
      $rules = $this->rules;
      $getActionName = request()->route()->getActionName();

      // 首页
      if(stripos($getActionName,'index'))
      {
         // $rules[ 'uuid' ] = 'required'; // uuid
      }

      return $rules;
   }

   public function messages ()
   {
      return $this->messages;
   }

}
