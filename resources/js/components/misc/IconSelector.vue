<template>
    <div class="icon-selector-wrap">
        <span
            v-on:click="selectedHandler(url)"
            v-for="(url, idx) in icons"
            :key="idx"
        >
            <el-avatar :src="url" class="the-icon"></el-avatar>
        </span>
    </div>
</template>

<script>
    import {Util} from "../../common/utils";
    export default {
        name: "IconSelector",
        data(){
            return {
                icons:[]
            }
        },
        created(){
            this.icons = Util.icons();
        },
        methods:{
            selectedHandler: function(url){
                this.$emit('icon-selected',{url:url});
            }
        }
    }
</script>

<style scoped lang="scss">
.icon-selector-wrap{
    .the-icon{
        margin: 10px;
        cursor: pointer;
        &:hover{
            background-color: #e0e0e0;
        }
    }
}
</style>