<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}
