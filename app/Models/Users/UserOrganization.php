<?php

namespace App\Models\Users;

use App\Models\School;
use App\Models\Schools\Organization;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'organization_id',
        'title_id',
        'title',
        'name',
    ];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function school(){
        return $this->belongsTo(School::class);
    }
    public function organization(){
        return $this->belongsTo(Organization::class);
    }
}
