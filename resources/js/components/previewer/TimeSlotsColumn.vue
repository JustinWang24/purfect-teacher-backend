<template>
    <div id="time-slots-wrap">
        <div class="header-box">

        </div>
        <div class="middle-box" v-for="(timeSlot, idx) in timeSlots" :key="idx">
            <p class="text-center mt-4">{{ timeSlot.name }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TimeSlotsColumn",
        props: {
            timeSlots: {
                type: Array,
                required: true
            }
        }
    }
</script>

<style scoped lang="scss">
#time-slots-wrap{
    .header-box{
        min-height: 40px;
    }
    .middle-box{
        font-size: 14px;
        font-weight: bold;
        color: #0c83fc;
        height: 140px;
        padding: 40px 15px 10px 0;
        box-sizing: border-box;
    }
}
</style>