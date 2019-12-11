<?php

namespace App\Http\Requests;

class RoleRequest extends MyStandardRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 获取提交的所有权限
     * @return array
     */
    public function getSubmittedPermissions(){
        $submittedPermissions = $this->all();
        $permissions = [];
        /**
         * @var string[] $allowedPermissions
         */
        $allowedPermissions = $this->getCurrentRoleAllowedPermissions();
        $allActions = $this->getAllActions();

        foreach ($allowedPermissions as $permissionName) {
            $slug = [];
            if(isset($submittedPermissions[$permissionName])){
                $submittedPermission = $submittedPermissions[$permissionName];
                foreach ($allActions as $actionName) {
                    $slug[$actionName] = isset($submittedPermission[$actionName]);
                }
            }
            else{
                $slug = $this->slugOfDisallowAll();
            }
            $permissions[] = [
                'slug'=>$slug,
                'name'=>$permissionName
            ];
        }
        return $permissions;
    }

    /**
     * 获取当前的角色的 slug
     * @return string
     */
    public function getCurrentRoleSlug(){
        return $this->get('slug');
    }

    /**
     * 获取当前角色可以操作的对象名的数组
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getCurrentRoleAllowedPermissions(){
        return config('acl.'.$this->getCurrentRoleSlug());
    }
}
