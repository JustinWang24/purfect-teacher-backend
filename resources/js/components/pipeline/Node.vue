<template>
    <div class="pipeline-node-component-wrap">
        <el-card :shadow="shadow" :class="highlight ? 'border-blue' : null">
            <div v-for="(act, idx) in node.actions" :key="idx">
                <action
                        :act="act"
                        :node-options="node.options"
                        :init-result="starter&&idx===0 ? '发起' : null"
                        :highlight="highlight">
                </action>
            </div>
            <div v-if="node.actions.length === 0">
                <pending-node-actions></pending-node-actions>
            </div>
        </el-card>
    </div>
</template>

<script>
    import Action from './Action.vue';
    import PendingNodeActions from './PendingNodeActions.vue';

    export default {
        name: "Node", // 'node','starter'
        props:{
            node: {
                required: true,
                type: Object
            },
            starter: {
                required: true,
                type: Boolean
            },
            highlight: {
                required: true,
                type: Boolean
            }
        },
        components:{
            Action, PendingNodeActions
        },
        computed:{
            'shadow': function(){
                return this.highlight ? 'always' : 'never';
            }
        },
        data(){
            return {}
        },
        created() {

        }
    }
</script>

<style scoped lang="scss">
.pipeline-node-component-wrap{
    .border-blue{
        border-color: #409EFF;
        background-color: #409EFF;
        p{
            color: white !important;
        }
    }
}
</style>