<template>
    <div class="block">
        <h2 class="title-bar">上课时间表</h2>
        <el-timeline class="frame-wrap">
            <el-timeline-item
                    v-for="(activity, index) in timeFrame"
                    :key="index"
                    :icon="activity.icon"
                    :type="activity.type"
                    :color="activity.color"
                    :size="activity.size"
                    :timestamp="activity.timestamp">
                {{activity.content}}
            </el-timeline-item>
        </el-timeline>
        <slot></slot>
    </div>
</template>
<script>
    import {Constants} from '../../common/constants';
    import {Util} from '../../common/utils';

    export default {
        name: "TimeSlotsManager",
        props: {
            school: {
                type: String,
                required: true
            },
            dotSize: {
                type: String,
                required: false,
                default: 'normal'
            }
        },
        data() {
            return {
                timeFrame: []
            };
        },
        mounted() {
            axios.post(
                Constants.API.LOAD_TIME_SLOTS_BY_SCHOOL,{school: this.school}
            ).then( res => {
                if(Util.isAjaxResOk(res)){
                    _.each(res.data.data.time_frame, (item) => {
                        this.timeFrame.push({
                            timestamp: item.from + ' - ' + item.to,
                            size: this.dotSize,
                            // color: '#0bbd87',
                            type: 'primary',
                            icon: '',
                            content: item.name
                        });
                    })
                }
            })
        },
        methods: {

        }
    }
</script>

<style scoped lang="scss">
.block{
    margin: 10px;
    .title-bar{
        display: block;
        line-height: 50px;
    }
    .frame-wrap{
        margin-top: 20px;
    }
}
</style>