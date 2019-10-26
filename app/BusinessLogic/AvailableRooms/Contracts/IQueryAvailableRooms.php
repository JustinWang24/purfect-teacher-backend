<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 2:15 PM
 */

namespace App\BusinessLogic\AvailableRooms\Contracts;


interface IQueryAvailableRooms
{
    public function getAvailableRooms();
}