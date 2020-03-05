<?php

namespace App\Models\Pipeline\Flow;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Copys extends Model
{
    public $table = 'pipeline_user_copys';
    protected $fillable = [
        'user_flow_id','user_id',
    ];

    public function userFlow(){
        return $this->belongsTo(UserFlow::class, 'user_flow_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
