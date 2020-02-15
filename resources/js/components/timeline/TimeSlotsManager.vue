<template>
    <div class="block">
        <h2 class="title-bar">作息时间表</h2>
        <el-timeline class="frame-wrap">
            <el-timeline-item
                    v-for="(activity, index) in timeFrame"
                    :key="index"
                    :icon="activity.icon"
                    :type="activity.type"
                    :color="activity.color"
                    :size="activity.size"
                    :timestamp="activity.timestamp">
                <p style="padding: 0;color: #409EFF;" v-on:click="editTimeSlot(activity, index)">
                    {{activity.content}}
                    &nbsp;
                    <i class="el-icon-check" v-if="index === highlightIdx"></i>
                </p>
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
                timeFrame: [],
                highlightIdx: -1,
            };
        },
        mounted() {
            const that = this;
            axios.post(
                Constants.API.LOAD_TIME_SLOTS_BY_SCHOOL,{school: this.school}
            ).then( res => {
                if(Util.isAjaxResOk(res)){
                    res.data.data.time_frame.forEach((item) => {
                        that.timeFrame.push({
                            timestamp: item.from + ' - ' + item.to,
                            size: this.dotSize,
                            // color: '#0bbd87',
                            type: 'primary',
                            icon: '',
                            content: item.name,
                            id: item.id,
                            origin: item
                        });
                    })
                }
            })
        },
        methods: {
            editTimeSlot: function(activity, index){
                this.highlightIdx = index;
                const timeSlot = Util.GetItemById(activity.id, this.timeFrame);
                this.$emit('edit-time-slot', {timeSlot: timeSlot.origin, schoolUuid: this.school});
            }
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