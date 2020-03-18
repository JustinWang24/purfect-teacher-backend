<template>
  <div v-if="src" class="avatar" :style="style"></div>
  <div class="avatar" v-else>
    <i class="el-icon-user-solid"></i>
  </div>
</template>
<script>
export default {
  name: "avatar",
  props: {
    size: {
      type: Number,
      default: 50
    },
    src: {
      type: String,
      default: null
    }
  },
  computed: {
    style() {
      let style = {
        backgroundImage: `url(${this.src})`
      };
      if (this.size) {
        style.width = this.size + "px";
        style.height = this.size + "px";
      }
      return style;
    }
  }
};
</script>
<style lang="scss" scoped>
.avatar {
  height: 50px;
  width: 50px;
  border-radius: 50%;
  background-position: center;
  background-repeat: no-repeat;
  margin-right: 8px;
  background-size: contain;
}
</style>