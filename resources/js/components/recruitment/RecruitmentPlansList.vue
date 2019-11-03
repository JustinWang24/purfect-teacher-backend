<template>
    
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';
    import axios from 'axios';

    export default {
        name: "RecruitmentPlansList",
        props:[
            'schoolId','userUuid','canDelete'
        ],
        data(){
            return {
                plans:[],
                reloading: false,
            }
        },
        created() {
            this.loadPlans();
        },
        methods: {
            loadPlans: function(){
                axios.post(
                    Constants.API.RECRUITMENT.LOAD_PLANS,
                    {school: this.schoolId}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        this.plans = res.data.data.plans;
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>