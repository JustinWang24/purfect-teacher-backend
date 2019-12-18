<?php
/**
 * 年级组长
 */
namespace App\Models\Schools;

use App\User;
use Illuminate\Database\Eloquent\Model;

class YearManager extends Model
{
    protected $fillable = [
        'school_id','year','user_id'
    ];
    public $timestamps = false;

    /**
     * 关联的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
