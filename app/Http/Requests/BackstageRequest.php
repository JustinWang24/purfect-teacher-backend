<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 17/10/19
 * Time: 11:49 AM
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Fun端 Api 接口请求
 * Class BackstageRequest
 * @package App\Http\Requests
 */
class BackstageRequest extends FormRequest
{
   public function __construct ()
   {
   }

   public function authorize ()
   {
      return true;
   }
   /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
   public function rules()
   {
      return [
         //
      ];
   }


}