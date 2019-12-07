<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 17/10/19
 * Time: 11:49 AM
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\MyStandardRequest;

/**
 * Fun端 Api 接口请求
 * Class ApiRequest
 * @package App\Http\Requests
 */
class ApiRequest extends MyStandardRequest
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