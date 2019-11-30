<?php

namespace App\Models\EnrolmentStep;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class EnrolmentStep extends Model
{

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name'];

}
