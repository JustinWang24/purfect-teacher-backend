<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/3/31
 * Time: 下午7:30
 */

namespace App\Http\Controllers\Api\OA;


use App\Http\Controllers\Controller;
use App\Utils\JsonBuilder;

class WorkController extends Controller
{

    /**
     * 考核
     * @return string
     */
    public function assess() {
        $data = [
            'url' => '',
            'image' => asset('/storage/users/171985/236795ca-9413-46bb-9812-316569e22b69.jpg'),
        ];
        return JsonBuilder::Success($data);
    }
}