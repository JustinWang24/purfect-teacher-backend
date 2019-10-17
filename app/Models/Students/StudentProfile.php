<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'gender',
        'country',
        'state',
        'city',
        'postcode',
        'address_line',
        'address_in_school',
        'device',
        'birthday',
        'avatar',
    ];

    public $dates = ['birthday'];
}
