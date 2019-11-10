<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/11/19
 * Time: 1:30 PM
 */

namespace App\BusinessLogic\QuickSearch\Impl;

class StudentQuickSearchLogic extends AbstractQuickSearchLogic
{
    public function getFacilities()
    {
        return [];
    }

    public function getNextAction($facility)
    {
        return '';
    }
}