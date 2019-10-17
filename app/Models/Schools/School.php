<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['uuid', 'name', 'max_students_number', 'max_employees_number'];


}
