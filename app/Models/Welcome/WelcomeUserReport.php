<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Welcome;

use Illuminate\Database\Eloquent\Model;

class WelcomeUserReport extends Model
{
    protected $table = 'welcome_user_reports';
    protected $fillable = [
        'configid',
        'uuid',
        'user_id',
        'flow_id',
        'school_id',
        'campus_id',
        'steps_1_str',
        'steps_1_date',
        'steps_2_str',
        'steps_2_date',
        'complete_date',
        'status',
        'created_at',
        'updated_at',
    ];
}
