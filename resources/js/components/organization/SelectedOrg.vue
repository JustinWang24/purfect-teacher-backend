<template>
  <span class="so-wrap">
    <el-dropdown size="small" split-button type="primary" class="no-outline" @command="onRemoveBtnClicked">
      {{ org.name }}
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item command="a">取消选择</el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </span>
</template>

<script>
    export default {
        name: "SelectedOrg",
        props:['org'],
        methods:{
            onRemoveBtnClicked: function(command){
              this.$emit('remove',{obj: this.org});
            }
        }
    }
</script>

<style scoped lang="scss">
  .so-wrap{
    .no-outline{
      margin: 5px;
      &:focus{
        outline:0;
      }
    }
  }
</style>