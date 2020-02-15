<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Welcome;

use Illuminate\Database\Eloquent\Model;

class WelcomeConfigStep extends Model
{
    protected $table = 'welcome_config_steps';
    protected $fillable = [
        'school_id',
        'campus_id',
        'config_id',
        'steps_id',
        'steps_json',
        'sort',
        'status',
        'created_at',
        'updated_at',
    ];
}
