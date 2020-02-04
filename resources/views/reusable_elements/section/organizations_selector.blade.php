@php
    /**
     * $organizationsSelectedHandler: 表示有任何组织或者机构，部门被选择后的处理函数
     * $schoolId: 表示学校的ID
     * $userRoles: 表示用户的角色集合
     * $syncFlag: 表示控制组件是否显示的变量名称
     */
@endphp
<el-drawer
        title="可见范围选择器"
        :visible.sync="{{ $syncFlag??'showOrganizationsSelectorFlag' }}"
        direction="rtl"
        size="100%"
        custom-class="drawer-organizations-selector">

</el-drawer>

<organizations-selector
        user-uuid="{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->uuid : ($user->uuid ?? null) }}"
        school-id="{{ $schoolId }}"
        roles="{{ $userRoles }}"
        {!! isset($organizationsSelectedHandler) ? 'v-on:organizations-selected="'.$organizationsSelectedHandler.'"' : null !!}
></organizations-selector>