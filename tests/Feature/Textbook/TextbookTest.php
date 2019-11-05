<?php

namespace Tests\Feature\Textbook;

use App\Models\Schools\Textbook;
use Illuminate\Support\Str;
use Tests\Feature\BasicPageTestCase;

class TextbookTest extends BasicPageTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function _createDate() {
        $data = [
            'name'     => Str::random(),
            'press'    => '新华出版社',
            'author'   => '三毛',
            'edition'  => '1',      //版本
            'course_id'=> 1,
            'type'     => Textbook::TYPE_MAJOR,
            'purchase_price' => 80,
            'price'    => 100,
        ];
        return $data;
    }

    public function testAddTextbookPage() {
        $this->withoutExceptionHandling();
        $user = $this->getSuperAdmin();

        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->get(route('school_manager.textbook.add'));

        $response->assertSee('input type="hidden" name="_token"');
        $response->assertSee('id="textbook-name-input"');
        $response->assertSee('id="textbook-press-input"');
        $response->assertSee('id="textbook-author-input"');
        $response->assertSee('id="textbook-edition-input"');
        $response->assertSee('id="textbook-course_id-select"');
        $response->assertSee('id="textbook-type-input"');
        $response->assertSee('id="purchase_price"');
        $response->assertSee('id="price"');


    }


    public function testAddTextbookApi() {

        $data = $this->_createDate();
        $user = $this->getSuperAdmin();
        $response = $this->setSchoolAsUser($user, 50)
            ->actingAs($user)
            ->withSession($this->schoolSessionData)
            ->post(route('school_manager.textbook.add',$data));

        dd($response);

    }
}
