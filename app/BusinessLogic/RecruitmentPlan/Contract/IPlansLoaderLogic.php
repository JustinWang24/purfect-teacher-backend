<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/11/19
 * Time: 6:08 PM
 */

namespace App\BusinessLogic\RecruitmentPlan\Contract;


interface IPlansLoaderLogic
{
    public function getPlans();

    public function getPlanDetail();
}