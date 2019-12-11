<?php


namespace App\BusinessLogic\DocumentsWorkflows;


use App\BusinessLogic\DocumentsWorkflows\Contracts\IPredefinedStep;
use App\BusinessLogic\DocumentsWorkflows\Impl\StepOne;
use Illuminate\Http\Request;

class Factory
{
    /**
     * @param Request $request
     * @return IPredefinedStep
     */
    public static function GetInstance(Request $request){
        $key =$request->get('step_key');
        $instance = null;

        switch ($key){
            case 1:
                // Todo: new 真实的实现类
                $instance = new StepOne($key);
                break;
            default:
                break;
        }

        return $instance;
    }
}
