<?php

namespace App\Models\Importer;

use Illuminate\Database\Eloquent\Model;

class ImoprtLog extends Model
{
    protected $table = 'import_log';
    protected $fillable = [
        'type', 'source', 'target', 'table_name', 'result', 'task_id', 'task_status',
    ];
}
