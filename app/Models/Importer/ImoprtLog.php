<?php

namespace App\Models\Importer;

use Illuminate\Database\Eloquent\Model;

class ImoprtLog extends Model
{
    const ADMIN_SUCCESS_STATUS  = 1; //管理员操作记录状态成功
    const ADMIN_FAIL_STATUS     = 2; //管理员操作记录状态失败
    const SUCCESS_STATUS        = 3; //学校管理员操作状态成功
    const FAIL_STATUS           = 4; //学校管理员操作状态失败
    protected $table = 'import_log';
    protected $fillable = [
        'type', 'source', 'target', 'table_name', 'result', 'task_id', 'task_status', 'school_id', 'only_flag'
    ];
}
