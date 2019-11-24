<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 15/11/19
 * Time: 8:59 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics\Contracts;


interface ICategoryLogic
{
    public function getCategoryByUuid();

    public function getAllSchoolRootCategory();

    public function getData();
}