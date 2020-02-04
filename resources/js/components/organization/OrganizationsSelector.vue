<template>
  <div class="org-selector-wrap">
    <h2>可见范围选择器</h2>
  </div>
</template>

<script>
    import {Util} from "../../common/utils";
    import {Constants} from "../../common/constants";
    import {loadOrganizationsByRoles} from '../../common/organizations';

    export default {
        name: "OrganizationsSelector",
        props:['userUuid','schoolId','roles'],
        data(){
            return {
                theEventName: 'organizations-selected', // 对外发布的时间名称
                bufferOrganizations:[], // 备选的机构
                selectedOrganizations:[], // 已经选择的机构
            }
        },
        created: function(){
            // 根据传入的用户，加载其可能的全部组织机构
            this.init();
        },
        methods:{
            init: function(){
                loadOrganizationsByRoles(this.schoolId, this.userUuid, this.roles)
                    .then(res => {

                    })
            }
        }
    }
</script>

<style scoped lang="scss">
.org-selector-wrap{

}
</style>