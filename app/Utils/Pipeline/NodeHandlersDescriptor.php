<?php
/**
 * 在创建流程的步骤时, 哪些用户可以使用/发起某个步骤, 需要用这个类来解析成数据库表要求的格式
 * User: justinwang
 * Date: 3/12/19
 * Time: 11:54 PM
 */

namespace App\Utils\Pipeline;
use App\Dao\Schools\OrganizationDao;
use App\Models\Schools\Organization;
use App\Utils\Misc\Contracts\Title;

class NodeHandlersDescriptor
{
    public static function Parse($data){
        // 可以使用, 或者说发起此流程的用户群体; $organizations优先, 如果 $organizations 是空数组, 那么才考虑 $handlers;
        $organizations = $data['organizations']??[];
        $titles = $data['titles']??[];
        $handlers = $data['handlers']??[];

        $handlersDescriptor = [];
        if(!empty($organizations)){
            $orgDao = new OrganizationDao();
            $orgStr = '';
            $titlesStr = '';
            // 对应的是 handlers 表中的 organizations 字段, 需要解析提交的 title 和 organizations
            foreach ($organizations as $organization) {
                /**
                 * @var array $organization : 这也是个数组, 其中后一个值, 代表了有效的组织的 id, 其余是其上层组织的 id. 保存时使用部门的真实名字
                 */
                $orgStr .= $orgDao->getById($organization[count($organization) - 1])->name.';';
            }

            // titles 表示的是所有可以使用这个流程的角色的中文名称
            if(in_array(Title::ALL_TXT, $titles)){
                foreach (Organization::AllTitles() as $title) {
                    $titlesStr .= $title.';';
                }
            }
            else{
                foreach ($titles as $title) {
                    $titlesStr .= $title.';';
                }
            }

            $handlersDescriptor['organizations'] = $orgStr;
            $handlersDescriptor['titles'] = $titlesStr;
        }
        elseif(!empty($handlers)){
            // 对应的是 handlers 表中的 role_slugs 字段
            $handlersDescriptor['role_slugs'] = '';
            foreach ($handlers as $slugText) {
                $handlersDescriptor['role_slugs'] .= $slugText.';';
            }
        }
        return $handlersDescriptor;
    }
}