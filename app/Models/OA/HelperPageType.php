<?php


namespace App\Models\OA;

use Illuminate\Database\Eloquent\Model;

class HelperPageType extends Model
{
    protected $table = 'oa_teacher_helper_type';

    protected $hidden = ['id', 'type', 'status', 'sort', 'created_at', 'updated_at', 'school_id'];

    const TYPE_COMMUNAL = '0';
    const TYPE_COMMUNAL_TEXT = '公共的';

    const STATUS_ERROR = 0;  // 不显示
    const STATUS_NORMAL = 1; // 显示


    public function helperPage()
    {
        return $this->hasMany(HelperPage::class,'type_id','id');
    }
}
