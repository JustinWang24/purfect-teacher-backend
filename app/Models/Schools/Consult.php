<?php
namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consult extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'question', 'answer', 'school_id', 'last_updated_by'
    ];
}
