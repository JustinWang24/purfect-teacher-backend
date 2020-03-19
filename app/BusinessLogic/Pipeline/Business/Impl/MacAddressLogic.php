<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 4/12/19
 * Time: 8:49 PM
 */

namespace App\BusinessLogic\Pipeline\Business\Impl;
use App\Models\TeacherAttendance\UserMac;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;

class MacAddressLogic
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
            UserMac::where('teacher_attendance_id', $options['attendance_id'])->where('user_id', $user->id)->update([
                'mac_address' => $options['mac_address']
            ]);
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }catch (\Exception $exception) {
            $bag->setMessage( $exception->getMessage());
        }
        return $bag;
    }

}
