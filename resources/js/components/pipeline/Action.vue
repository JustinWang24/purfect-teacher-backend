<template>
    <div class="pipeline-action-component-wrap">
        <el-row>
            <el-col :span="2">
                <el-avatar :size="size" :src="act.personal.avatar"></el-avatar>
            </el-col>
            <el-col :span="22">
                <p :class="highlight ? 'text-white' : null">
                    {{ act.personal.name }}: <span :class="resultTextClass(act.result)">{{ resultText(act.result) }}</span>
                    <span class="text-grey"><i class="el-icon-alarm-clock pr-2 pl-2"></i>{{ clockText }}</span>

                    <el-tag v-if="act.urgent" type="danger">申请加急</el-tag>
                </p>
                <p  :class="highlight ? 'text-white' : null">{{ quoteText2 }}:</p>
                <p  :class="highlight ? 'text-white' : null">{{ description }}</p>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';
    export default {
        name: "Action",
        props:{
            act: {
                required: true,
                type: Object
            },
            initResult: {
                required: false,
                type: String,
                default: ''
            },
            size: {
                required: false,
                type: String,
                default: 'medium'
            },
            highlight: {
                required: true,
                type: Boolean,
            }
        },
        data(){
            return {
            };
        },
        computed:{
            'clockText': function(){
                return this.act.updated_at.substring(0, 16);
            },
            'quoteText1': function(){
                if(!Util.isEmpty(this.initResult)){
                    return '';
                }else{
                    return '审核结果:';
                }
            },
            'quoteText2': function(){
                if(!Util.isEmpty(this.initResult)){
                    return '事由说明';
                }else{
                    return '审核意见';
                }
            },
            'description': function(){
                return Util.isEmpty(this.act.content) ? '无' : this.act.content;
            }
        },
        created() {

        },
        methods: {
            resultText: function(result){
                if(!Util.isEmpty(this.initResult)){
                    return this.initResult;
                }
                let txt = Constants.FLOW_ACTION_RESULT.PENDING_TXT;
                switch (result){
                    case Constants.FLOW_ACTION_RESULT.PASSED:
                        txt = Constants.FLOW_ACTION_RESULT.PASSED_TXT;
                        break;
                    case Constants.FLOW_ACTION_RESULT.NOTICED:
                        txt = Constants.FLOW_ACTION_RESULT.NOTICED_TXT;
                        break;
                    case Constants.FLOW_ACTION_RESULT.REJECTED:
                        txt = Constants.FLOW_ACTION_RESULT.REJECTED_TXT;
                        break;
                    case Constants.FLOW_ACTION_RESULT.TERMINATED:
                        txt = Constants.FLOW_ACTION_RESULT.TERMINATED_TXT;
                        break;
                    default:
                        break;
                }
                return txt;
            },
            resultTextClass: function(result){
                if(!Util.isEmpty(this.initResult)){
                    return Constants.FLOW_ACTION_RESULT.PASSED_CLASS;
                }

                let txt = Constants.FLOW_ACTION_RESULT.PENDING_CLASS;
                switch (result){
                    case Constants.FLOW_ACTION_RESULT.PASSED:
                        txt = Constants.FLOW_ACTION_RESULT.PASSED_CLASS;
                        break;
                    case Constants.FLOW_ACTION_RESULT.NOTICED:
                        txt = Constants.FLOW_ACTION_RESULT.NOTICED_CLASS;
                        break;
                    case Constants.FLOW_ACTION_RESULT.REJECTED:
                        txt = Constants.FLOW_ACTION_RESULT.REJECTED_CLASS;
                        break;
                    case Constants.FLOW_ACTION_RESULT.TERMINATED:
                        txt = Constants.FLOW_ACTION_RESULT.TERMINATED_CLASS;
                        break;
                    default:
                        break;
                }
                return txt;
            }
        }
    }
</script>

<style scoped lang="scss">
.pipeline-action-component-wrap{
    .text-grey{
        color: #e0e0e0;
    }
}
</style>