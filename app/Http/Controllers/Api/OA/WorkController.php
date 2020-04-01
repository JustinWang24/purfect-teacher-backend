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
            'image' => asset('assets/img/rk.png'),
        ];
        return JsonBuilder::Success($data);
    }
}
