<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 11:50 AM
 */

namespace Tests\Unit\Dao;
use App\Dao\Schools\CampusDao;
use Tests\TestCase;
use App\User;
class CampusDaoTest extends TestCase
{
    public function testItCanGetCampusById(){
        $dao = new CampusDao(new User());
        $this->assertNotNull($dao->getCampusById(1));
    }
}