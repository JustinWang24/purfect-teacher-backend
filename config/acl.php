<?php

return [

    /**
     * Model definitions.
     * If you want to use your own model and extend it
     * to package's model. You can define your model here.
     */

    'role'       => 'App\Models\Acl\Role', // 子定义的 Role 模型类
    'permission' => 'App\Models\Acl\Permission',    // 自定义的 Permission 模型类

    /**
     * Most Permissive Wins right
     * If you have multiple permission aliases assigned, each alias
     * has a common permission, view.house => false, but one alias
     * has it set to true. If this right is enabled, true value
     * wins the race, ie the most permissive wins.
     */

    'most_permissive_wins'       => false,

    /**
     * Cache Minutes
     * Set the minutes that roles and permissions will be cached.
     */
		
    'cacheMinutes' => 1,

    /**
     * 系统预定义的权限
     */
    'permissions' => [
        // 学校的基础数据
        'students','teachers','schools','campuses','institutes','majors','buildings','rooms','roles','permissions',
        // 附加的业务数据
        'scenery',
    ],
    'actions'=>[
        'create','view','update','delete','list'
    ]
];
