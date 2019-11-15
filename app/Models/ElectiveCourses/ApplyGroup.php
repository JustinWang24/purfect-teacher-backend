<?php


namespace App\Models\ElectiveCourses;
use Illuminate\Database\Eloquent\Model;

class ApplyGroup extends Model
{
    protected $fillable = [
        'apply_id'
    ];


    public function week(){
        return $this->hasMany(ApplyWeek::class,'group_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($applyGroup) {
            $applyGroup->week()->delete();
        });
    }


}
