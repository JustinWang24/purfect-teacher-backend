<?php
/**
 * 表示系统内部消息是否已读的模型
 */
namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;

class SystemNotificationsReadLog extends Model
{
    protected $fillable = [
        'system_notifications_maxid',
        'user_id',
    ];
}
