<template>
  <span class="operater">{{name}}</span>
</template>
<script>
export default {
  name: "user-link",
  props: {
    name: {
      type: String,
      required: true
    },
    id: {
      default: null
    }
  }
};
</script>
<style lang="scss" scoped>
.operater {
  color: #4ea5fe;
  cursor: pointer;
}
.operater:hover {
  text-decoration: underline;
}
</style>