<?php


namespace App\Models\OA;

use Illuminate\Database\Eloquent\Model;

class HelperPage extends Model
{
    protected $table = 'oa_teacher_helper_page';

    protected $hidden = ['id', 'type_id', 'sort', 'created_at', 'updated_at'];
    
    public function getIconAttribute($value)
    {
        return asset($value);
    }
}
