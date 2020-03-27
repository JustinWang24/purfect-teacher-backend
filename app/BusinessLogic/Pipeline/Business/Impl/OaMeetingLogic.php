<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Business\Impl;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;

class OaMeetingLogic
{
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle($options)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        try {
            //@TODO 操作newMeeting
            /*$options = [
             *   'meet_id' => 1,
             *   'pipeline_done' => 1, 1同意 2拒绝
            ]*/
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }catch (\Exception $exception) {
            $bag->setMessage( $exception->getMessage());
        }
        return $bag;
    }

}
