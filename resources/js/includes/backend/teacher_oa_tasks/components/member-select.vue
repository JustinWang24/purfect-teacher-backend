<template>
  <div class="container" v-show="members.length > 0">
    <el-checkbox-group v-model="checkList">
      <el-checkbox
        v-for="(member, index) in members"
        :key="index"
        :label="member.id"
        >{{ member.name }}</el-checkbox
      >
    </el-checkbox-group>
  </div>
</template>

<script>
export default {
  name: "member-select",
  props: {
    members: {
      type: Array,
      required: true,
      default: []
    }
  },
  watch: {
    members: {
      deep: true,
      immediate: true,
      handler(value) {
        this.checkList = [];
      }
    },
    checkList: {
      deep: true,
      immediate: true,
      handler(value) {
        debugger;
        this.$emit("input", this.checkList);
      }
    }
  },
  data() {
    return {
      checkList: []
    };
  }
};
</script>
<style lang="scss" scoped>
.container {
  padding: 12px;
  border-top: 1px solid #e4e7ed;
}
</style>
