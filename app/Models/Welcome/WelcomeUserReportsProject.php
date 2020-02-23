<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Welcome;

use Illuminate\Database\Eloquent\Model;

class WelcomeUserReportsProject extends Model
{
    protected $table = 'welcome_user_reports_projects';
    protected $fillable = [
        'projectid',
        'uuid',
        'typeid',
        'user_id',
        'school_id',
        'campus_id',
        'project_title',
        'project_desc',
        'project_desc',
        'status',
        'created_at',
        'updated_at',
    ];
}
