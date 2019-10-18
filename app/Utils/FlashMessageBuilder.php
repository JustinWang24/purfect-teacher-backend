<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 12:01 PM
 */

namespace App\Utils;
use Illuminate\Http\Request;

class FlashMessageBuilder
{
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const DANGER = 'danger';

    /**
     * @param Request $request
     * @param string $type
     * @param string $content
     */
    public static function Push(Request $request, $type, $content){
        $request->session()->flash('msg',['status'=>$type, 'content'=>$content]);
    }
}