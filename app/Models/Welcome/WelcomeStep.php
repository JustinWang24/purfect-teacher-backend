<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Models\Welcome;

use Illuminate\Database\Eloquent\Model;

class WelcomeStep extends Model
{
    protected $table = 'welcome_steps';
    protected $fillable = [
        'name',
        'letter',
        'icon',
        'status',
        'created_at',
        'updated_at',
    ];
}
