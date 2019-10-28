<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 2:13 PM
 */

namespace App\BusinessLogic\AvailableRooms;

use App\BusinessLogic\AvailableRooms\TimetableApi\QueryAvailableClassrooms;
use Illuminate\Http\Request;
use App\BusinessLogic\AvailableRooms\Contracts\IQueryAvailableRooms;

class Factory
{
    /**
     * @param Request $request
     * @return IQueryAvailableRooms
     */
    public static function GetLogic(Request $request){
        $instance = null;

        $instance = new QueryAvailableClassrooms($request);

        return $instance;
    }
}