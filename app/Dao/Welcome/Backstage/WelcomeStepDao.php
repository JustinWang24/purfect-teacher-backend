<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Welcome\Backstage;

use App\Models\Welcome\WelcomeStep;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class WelcomeStepDao extends \App\Dao\Welcome\CommonDao
{
    public function __construct()
    {
    }
}
