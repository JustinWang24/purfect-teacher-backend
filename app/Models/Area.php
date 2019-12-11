<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Area
 * @property integer linkageid
 * @property string  name
 * @property string  parentid
 * @property int     level
 * @package App\Models
 */
class Area extends Model
{
    protected $table = 't_api_linkage';

    const LEVEL_PROVINCES = 1;  // 省
    const LEVEL_CITIES    = 2;  // 市
    const LEVEL_DISTRICTS = 3;  // 区


}
