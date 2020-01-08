<?php


namespace App\Models\OA;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'oa_groups';

    protected $fillable = ['name', 'user_id', 'school_id'];

    public function groupMember() {
        return $this->hasMany(GroupMember::class,'group_id' );
    }
}
