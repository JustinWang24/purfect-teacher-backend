<?php


namespace App\Models\Notices;

use Illuminate\Database\Eloquent\Model;

class AppProposal extends Model
{

    // 反馈类型
    const TYPE_1 = '易码通';
    const TYPE_2 = '办事大厅';
    const TYPE_3 = '学习';
    const TYPE_4 = '生活';
    const TYPE_5 = '就业';
    const TYPE_6 = '其他';

    protected $fillable = ['user_id', 'type', 'content'];


}
