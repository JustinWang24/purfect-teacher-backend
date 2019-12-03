<?php

use Illuminate\Database\Seeder;
use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\Node;
use App\Models\Schools\Organization;
use App\Models\Users\UserOrganization;

class FlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建一些测试的数据: 所有数据, 默认都是以学校 1 为例

        // 创建一个流程:
        $flow = factory(Flow::class)->create();

        // 生成 10 个节点
        /**
         * @var \Illuminate\Support\Collection $nodes
         */
        $nodes = factory(Node::class, 10)->create();
        $nodes->each(function($node, $key) use ($flow, $nodes) {
            $node->flow_id = $flow->id;
            if($key === 0){
                // head node
                $node->next_node = $nodes->get(1)->id;
            }
            elseif($key < count($nodes) - 1){
                $node->prev_node = $nodes->get($key - 1)->id;
                $node->next_node = $nodes->get($key + 1)->id;
            }
            elseif ($key === count($nodes) - 1){
                $node->prev_node = $nodes->get($key - 1)->id;
            }
            $node->save();
        });

        // 生成几个组织机构和用户
        /**
         * @var \Illuminate\Support\Collection $orgs
         */
        $orgs = factory(Organization::class, 3)->create();
        $orgs->each(function ($org) {
            // 每个组织机构创建一个主管, 2 个副职, 4 个文员

            $uos = factory(UserOrganization::class, 7)->make();
            $uos->each(function($userOrg, $key) use ($org){
                $user = factory(\App\User::class)->make();
                $user->type = \App\Models\Acl\Role::EMPLOYEE;
                $user->save();

                $userOrg->organization_id = $org->id;
                $userOrg->user_id = $user->id;

                if($key === 0){
                    // 主管
                    $userOrg->title_id = \App\Utils\Misc\Contracts\Title::LEADER;
                    $userOrg->title = \App\Utils\Misc\Contracts\Title::LEADER_TXT;
                }
                elseif ($key === 1 || $key === 2){
                    // 2 个副职
                    $userOrg->title_id = \App\Utils\Misc\Contracts\Title::DEPUTY;
                    $userOrg->title = \App\Utils\Misc\Contracts\Title::DEPUTY_TXT;
                }
                else{
                    $userOrg->title_id = \App\Utils\Misc\Contracts\Title::MEMBER;
                    $userOrg->title = \App\Utils\Misc\Contracts\Title::MEMBER_TXT;
                }

                $userOrg->save();
            });
        });
    }
}
