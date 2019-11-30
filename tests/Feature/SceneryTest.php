<?php

namespace Tests\Feature;

use Tests\Feature\BasicPageTestCase;

class SceneryTest extends BasicPageTestCase
{

    /**
     * 测试可以正确看到学校风采列表(管理端)
     */
    public function testItCanSeeSchoolSceneryListView()
    {
        $sm = $this->getSchoolManager();
        $response = $this->setSchoolAsUser($sm, 1)
                    ->actingAs($sm)
                    ->withSession($this->schoolSessionData)
                    ->get(route('school_manager.scenery.list'));

        $response->assertSee('class="btn btn-primary pull-right"');
        $response->assertSee('class="table-responsive"');
        $response->assertSee('class="thumbnail"');
    }

     /**
     * 测试添加学校风采表单(管理端)
     */
    public function testItCanSeeSceneryCreateForm()
    {
        $sm = $this->getSchoolManager();

        $response = $this->setSchoolAsUser($sm, 1)
                    ->actingAs($sm)
                    ->withSession($this->schoolSessionData)
                    ->get(route('school_manager.scenery.add'));

        $response->assertSee('for="manager-type-input"');
        $response->assertSee('for="manager-name-input"');
        $response->assertSee('for="manager-width-input"');
        $response->assertSee('for="manager-height-input"');
        $response->assertSee('for="manager-image-input"');
        $response->assertSee('for="manager-video-cover-input"');
        $response->assertSee('for="manager-video-input"');
        $response->assertSee('id="image"');
        $response->assertSee('class="form-group"');
        $response->assertSee('id="video"');
    }

    /**
     * 测试修改学校风采表单(管理端)
     */
    public function testItCanSeeSceneryEditForm()
    {
        $sm = $this->getSchoolManager();

        $response = $this->setSchoolAsUser($sm, 1)
                    ->actingAs($sm)
                    ->withSession($this->schoolSessionData)
                    ->get(route('school_manager.scenery.add'));

        $response->assertSee('id="manager-id-input"');
        $response->assertSee('for="manager-type-input"');
        $response->assertSee('for="manager-name-input"');
        $response->assertSee('for="manager-width-input"');
        $response->assertSee('for="manager-height-input"');
        $response->assertSee('for="manager-image-input"');
        $response->assertSee('for="manager-video-cover-input"');
        $response->assertSee('for="manager-video-input"');
        $response->assertSee('id="image"');
        $response->assertSee('class="form-group"');
        $response->assertSee('id="video"');


    }


    /**
     * 测试可以正确看到学校风采(教师端)
     */
    public function testItCanSeeSchoolSceneryView()
    {
        $sm = $this->getTeacher();
        $response = $this->setSchoolAsUser($sm, 1)
                    ->actingAs($sm)
                    ->withSession($this->schoolSessionData)
                    ->get(route('teacher.scenery.index'));

        $response->assertSee('class="col-sm-12 col-md-6 offset-md-2"');
        $response->assertSee('class="blogThumb"');
        $response->assertSee('class="img-responsive"');
        $response->assertSee('class="course-box"');
    }



}
