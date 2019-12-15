<template>
    <div class="pipeline-action-component-wrap">
        <el-row>
            <el-col :span="6">
                <el-avatar :size="size" :src="act.personal.avatar"></el-avatar>
                <el-tag style="margin-top: 10px;" size="small" v-if="act.urgent" type="danger">加急</el-tag>
            </el-col>
            <el-col :span="18">
                <p :class="highlight ? 'text-white' : null">
                    {{ act.personal.name }}: <span :class="resultTextClass(act.result)">{{ resultText(act.result) }}</span>
                    <span class="text-grey" v-if="showClock">
                        <i class="el-icon-alarm-clock pr-2 pl-2"></i>{{ clockText }}
                    </span>
                </p>

                <ul v-if="act.options.length > 0" style="list-style:none;padding-left:0;">
                    <li v-for="ao in act.options" :key="ao.id">
                        {{ getOptionNameText(ao) }}: <span class="text-primary">{{ ao.value }}</span>
                    </li>
                </ul>

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
        name: "ActionMobile",
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
            },
            // 步骤所要求的必填项
            nodeOptions: {
                type: Array,
                required: false,
                default:[]
            }
        },
        data(){
            return {
            };
        },
        computed:{
            'clockText': function(){
                return this.showClock ? this.act.updated_at.substring(0, 16) : '';
            },
            'showClock': function(){
                return this.act.result !== Constants.FLOW_ACTION_RESULT.PENDING
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
            },
            getOptionNameText: function(ao){
                const idx = Util.GetItemIndexById(ao.option_id, this.nodeOptions);
                if(!Util.isEmpty(idx)){
                    return this.nodeOptions[idx].name;
                }
                return '';
            }
        }
    }
</script>

<style scoped lang="scss">
.pipeline-action-component-wrap{
    p{
        margin-top: 0;
        margin-bottom: 6px;
    }
    .text-grey{
        color: #e0e0e0;
    }
}
</style>