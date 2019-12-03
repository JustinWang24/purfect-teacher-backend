<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\Node;
use App\Models\Pipeline\Flow\Handler;
use App\Models\Schools\Organization;
use App\Models\Users\UserOrganization;

// 工作流引擎部分
// 1: 流程 mock
$factory->define(Flow::class, function (Faker $faker) {
    return [
        'school_id'=>1,
        'name'=>$faker->jobTitle,
        'type'=>\App\Utils\Pipeline\IFlow::TYPE_1,
    ];
});

// 2: 流程节点 mock
$factory->define(Node::class, function (Faker $faker) {
    return [
        'flow_id'=>0,
        'prev_node'=>0,
        'next_node'=>0,
        'thresh_hold'=>1,
        'type'=>\App\Utils\Pipeline\INode::TYPE_SIMPLE,
        'dynamic'=>true,
        'name'=>$faker->jobTitle,
        'description'=>$faker->paragraph,
    ];
});

// 3: 流程节点处理器函数 mock
$factory->define(Organization::class, function (Faker $faker) {
    return [
        'school_id'=>1,
        'name'=>$faker->name,
        'level'=>1,
        'parent_id'=>0,
        'phone'=>$faker->phoneNumber,
        'description'=>$faker->paragraph,
        'address'=>$faker->address,
    ];
});
$factory->define(UserOrganization::class, function (Faker $faker) {
    return [
        'school_id'=>1,
        'user_id'=>0,
        'organization_id'=>0,
        'title_id'=>\App\Utils\Misc\Contracts\Title::MEMBER,
        'title'=>\App\Utils\Misc\Contracts\Title::MEMBER_TXT,
        'name'=>$faker->name,
    ];
});
$factory->define(Handler::class, function (Faker $faker) {
    return [
        'node_id'=>0,
        'user_id'=>0,
        'organization_id'=>0,
        'title_id'=>0,
    ];
});