<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/10/19
 * Time: 2:16 PM
 */

namespace App\BusinessLogic\HomePage\Contracts;


interface IHomePageLogic
{
    /**
     * @return array
     */
    public function getDataForView();
}