<template>
  <span class="cts-wrap">
      <el-button size="small" @click="onClicked" class="no-outline">
          {{ obj.name }} <i class="el-icon-more" v-if="hasChildren"></i>
      </el-button>
  </span>
</template>

<script>
    export default {
        name: "ClickToSelect",
        props:['type','hasChildren','level','obj'],
        data(){
            return {
                selected: false,
            }
        },
        methods:{
            onClicked: function(){
                this.selected = !this.selected;
                let action = null;
                if(this.hasChildren){
                    action = 'load';
                }else{
                    action = this.selected ? 'add' : 'remove';
                }
                this.$emit('item-clicked',{
                    type:this.type,
                    action: action,
                    obj: this.obj,
                    level: this.level,
                    hasChildren: this.hasChildren
                })
            }
        }
    }
</script>

<style scoped lang="scss">
.cts-wrap{
  .no-outline{
    margin: 5px;
    &:focus{
      outline:0;
    }
  }
}
</style>