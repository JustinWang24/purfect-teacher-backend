<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Welcome;

use Illuminate\Database\Eloquent\Model;

class WelcomeProject extends Model
{
    protected $table = 'welcome_projects';
    protected $fillable = [
        'typeid',
        'cate_id',
        'school_id',
        'type_name',
        'sort',
        'status',
        'created_at',
        'updated_at',
    ];
}
