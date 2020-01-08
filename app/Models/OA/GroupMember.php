<?php


namespace App\Models\OA;


use App\User;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $table = 'oa_group_users';

    protected $fillable = ['school_id', 'group_id', 'user_id'];


    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
