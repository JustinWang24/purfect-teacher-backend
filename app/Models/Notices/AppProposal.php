<?php


namespace App\Models\Notices;

use Illuminate\Database\Eloquent\Model;

class AppProposal extends Model
{

    // 反馈类型
    const TYPE_1 = '欢聚易堂';
    const TYPE_2 = '外卖';
    const TYPE_3 = '求职';
    const TYPE_4 = 'Wifi';
    const TYPE_5 = '课表';
    const TYPE_6 = '其他';
    const TYPE_7 = '课件';
    const TYPE_8 = '党建';
    const TYPE_9 = '办公';
    const TYPE_10 = '社区';
    const TYPE_11 = '认证';



    protected $fillable = ['user_id', 'type', 'content'];


}
