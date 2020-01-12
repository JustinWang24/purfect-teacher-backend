<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 16:46 AM
 */
namespace App\Http\Requests\Api\Welcome;

/**
 * Class IndexRequest
 * @package App\Http\Requests\Api\Welcome
 */
class IndexRequest extends \App\Http\Requests\ApiRequest
{
   public $rules = [];

   protected $messages = [];

   public function rules ()
   {
      $rules = $this->rules;
	  
      $getActionName = request()->route()->getActionName();

      if(stripos($getActionName,'userinfo'))
      {
      }

      return $rules;
   }

   public function messages ()
   {
      return $this->messages;
   }

}
