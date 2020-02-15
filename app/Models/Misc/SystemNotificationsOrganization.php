<?php
/**
 * 表示系统内部消息权限组织的模型
 */
namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;

class SystemNotificationsOrganization extends Model
{
    protected $fillable = [
        'system_notifications_id',
        'organization_id',
    ];
}
