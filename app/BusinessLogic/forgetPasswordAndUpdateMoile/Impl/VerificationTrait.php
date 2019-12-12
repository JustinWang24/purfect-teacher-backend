<?php


namespace App\BusinessLogic\forgetPasswordAndUpdateMoile\Impl;


use App\Dao\Users\UserDao;
use App\Dao\Users\UserVerificationDao;
use App\Models\Users\UserVerification;
use App\Utils\JsonBuilder;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

trait VerificationTrait
{

    public function verificationCode($purpose)
    {
        $dao = new UserVerificationDao;

        $verification = $dao->getVerificationByMobileAdnCode($this->mobile, $this->code, $purpose);

        return $verification;
    }


    public function verificationPassword($password)
    {
        $userDao = new UserDao;
        return $userDao->getUserByMobile($this->mobile);
    }

    public function verificationUser()
    {
        $userDao = new UserDao;

        $user = $userDao->getUserByMobile($this->mobile);

        return $user;
    }


    public function updateToken($userId)
    {
        $userDao = new UserDao;
        $userDao->updateApiToken($userId, Uuid::uuid4()->toString());
    }
}
