<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 5/12/19
 * Time: 2:18 PM
 */

namespace App\Events;
use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;

interface IFlowAccessor
{
    public function getFlow():IFlow;
    public function getNode():INode;
    public function getAction():IAction;
    public function getUser():User;
}