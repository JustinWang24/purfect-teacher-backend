<template>
    
</template>
<script>
    import {Constants} from '../../common/constants';

    export default {
        name: "TimeSlotsManager",
        props: {
            school: {
                type: String,
                required: true
            }
        },
        data() {
            return {};
        },
        mounted() {
            console.log('TimeSlotsManager Component mounted.' + this.school);
            console.log(Constants.AJAX_SUCCESS);
        }
    }
</script>

<style scoped>

</style>