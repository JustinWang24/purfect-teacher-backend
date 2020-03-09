<?php

namespace App\Models\OA;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    const NOT_VISITED = 1;
    const VISITED = 2;

    // 状态(1:已分享,2:已填写,3:已使用)
    private static $statusArr = [
        1 => '已分享',
        2 => '未到访',
        3 => '已到访',
    ];

    /**
     * Func 获取状态
     * @param int $status
     * @return array|mixed
     */
    public function getStatusArr($status = 0){
        if ($status > 0) {
            return self::$statusArr[$status];
        }
        return self::$statusArr;
    }

    protected $fillable = [
        'id','uuid','user_id','school_id','invited_by','cate_id','name','mobile','vehicle_license',
        'reason','visitors_json1','visitors_json2','share_url','qrcode_url','status','scheduled_at','arrived_at'
    ];

    public $casts = [
        'scheduled_at'=>'datetime',
        'arrived_at'=>'datetime',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function invitedBy(){
        return $this->belongsTo(User::class,'invited_by');
    }
}
