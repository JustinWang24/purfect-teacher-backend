<template>
  <el-input
    :value="value"
    class="input"
    :readonly="readonly"
    :disabled="disabled"
    @input="onInput"
    @change="onChange"
  >
    <slot slot="prefix" name="prefix" />
    <slot slot="suffix" name="suffix" />
    <slot slot="prepend" name="prepend" />
    <slot slot="append" name="append" />
  </el-input>
</template>
<script>
export default {
  name: "NumberInpuit",
  props: {
    decimalLen: {
      type: Number,
      default: null
    },
    value: {
      type: [String, Number],
      default: ""
    },
    minus: {
      type: Boolean,
      default: false
    },
    readonly: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    suffix: {
      type: String,
      default: null
    },
    prefix: {
      type: String,
      default: null
    }
  },
  methods: {
    onInput(value, isChange) {
      if (!this.decimalLen) {
        if (this.minus) {
          if (value.startsWith("-")) {
            value =
              "-" + value.substring(1, value.length).replace(/[^\d]/g, "");
          } else {
            value = value.replace(/[^\d]/g, "");
          }
        } else {
          value = value.replace(/[^\d]/g, "");
        }
      } else {
        if (this.minus) {
          if (value.startsWith("-")) {
            if (isNaN(value)) {
              value = "-";
            } else {
              if (
                value.includes(".") &&
                value.split(".").pop().length > this.decimalLen
              ) {
                value = parseFloat(value).toFixed(this.decimalLen);
              }
            }
          } else {
            if (isNaN(value)) {
              value = "";
            } else {
              if (
                value.includes(".") &&
                value.split(".").pop().length > this.decimalLen
              ) {
                value = parseFloat(value).toFixed(this.decimalLen);
              }
            }
          }
        } else {
          if (value.startsWith("-")) {
            if (isNaN(value)) {
              value = "";
            } else {
              if (
                value.includes(".") &&
                value.split(".").pop().length > this.decimalLen
              ) {
                value = parseFloat(value).toFixed(this.decimalLen);
              }
              value = value.substring(1, value.length);
            }
          } else {
            if (isNaN(value)) {
              value = "";
            } else {
              if (
                value.includes(".") &&
                value.split(".").pop().length > this.decimalLen
              ) {
                value = parseFloat(value).toFixed(this.decimalLen);
              }
            }
          }
        }
      }
      if (isChange) {
        return value;
      }
      this.$emit("input", value);
    },
    onChange(value) {
      value = this.onInput(value, true);
      if (isNaN(value) || value === "") {
        return;
      }
      if (this.decimalLen) {
        value = parseFloat(value).toFixed(this.decimalLen);
        this.$emit("input", value);
      }
    }
  }
};
</script>