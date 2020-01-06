<?php

namespace App\Models\Importer;

use App\Models\School;
use Illuminate\Database\Eloquent\Model;

class ImoprtTask extends Model
{

    protected $table = 'import_task';
    protected $fillable = [
        'status', 'title', 'manager_id', 'file_path', 'config', 'file_info','school_id'
    ];

    public function school() {
        return $this->belongsTo(School::class);
    }
}
