<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Affiche;

use Illuminate\Database\Eloquent\Model;

class CollegeGroupPics extends Model
{
    protected $table = 'college_group_pics';
    protected $fillable = [
        'user_id',
        'group_id',
        'pics_smallurl',
        'pics_bigurl',
        'status',
        'created_at',
        'updated_at',
    ];
}
