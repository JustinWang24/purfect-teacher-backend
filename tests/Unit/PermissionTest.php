<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Acl\Role;

class PermissionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItCanCreateRole()
    {
        $role = new Role();
        $role->name = 'user'.time();
        $role->slug = 'some'.time();
        $role->description = 'dummy data';
        $result = $role->save();
        $this->assertTrue($result);
    }
}
