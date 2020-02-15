<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Welcome;

use Illuminate\Database\Eloquent\Model;

class WelcomeConfig extends Model
{
    protected $table = 'welcome_configs';
    protected $fillable = [
        'school_id',
        'campus_id',
        'config_content1',
        'config_content2',
        'config_sdata',
        'config_edate',
        'config_menu',
        'status',
        'created_at',
        'updated_at',
    ];
}
