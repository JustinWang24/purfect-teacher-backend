<template>
  <div>
    <el-input
      type="text"
      class="member-search"
      v-model="keywords"
      @input="searchMember"
      placeholder="查询"
    ></el-input>
    <el-cascader-panel
      v-show="!keywords"
      ref="cascader"
      :class="{'bordered': memberListSearch.length > 0 || memberListGroup.length > 0 || chosenList.length > 0}"
      :props="memberOptions"
      @expand-change="onNodeChange"
    ></el-cascader-panel>
    <div class="members">
      <div
        class="chosen-list"
        :class="{'bordered': (memberListSearch.length > 0 && !!keywords) || (memberListGroup.length > 0 && !keywords)}"
        v-if="chosenList.length > 0"
      >
        <div class="item" v-for="(member, index) in chosenList" :key="member.id">
          <span>{{member.name}}</span>
          <i class="el-icon-error" @click="removeChosen(member, index)"></i>
        </div>
      </div>
      <div class="member-list" v-show="memberListSearch.length > 0 || memberListGroup.length > 0">
        <div class="list-search chose-list" v-if="!!keywords">
          <div
            class="item"
            @click="selectMember(member)"
            :class="{'disabled': chosenMap[member.id] || disabledList.includes(member.id)}"
            v-for="member in memberListSearch"
            :key="member.id"
          >{{member.name}}</div>
        </div>
        <div class="list-org chose-list" v-else>
          <div
            class="item"
            @click="selectMember(member)"
            :class="{'disabled': chosenMap[member.id] || disabledList.includes(member.id)}"
            v-for="member in memberListGroup"
            :key="member.id"
          >{{member.name}}</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { searchMemberDebounce } from "../common/utils";
import { Util } from "../../../../common/utils";
export default {
  name: "member-select",
  props: {
    parentId: {
      require: true
    },
    disabledList: {
      type: Array,
      default: []
    }
  },
  data() {
    let that = this;
    return {
      chosenList: [],
      chosenMap: {},
      memberListSearch: [],
      membersMap: {},
      keywords: "",
      currentNodeId: "",
      memberOptions: {
        lazy: true,
        value: "id",
        multiple: false,
        label: "name",
        lazyLoad(node, resolve) {
          let parentId = null;
          if (!Util.isEmpty(node.data)) {
            parentId = node.data.id;
          }
          TaskApi.excute("getOrganization", {
            parent_id: parentId
          }).then(res => {
            resolve(res.data.data.organ);
            that.membersMap[parentId] = res.data.data.members;
          });
        }
      }
    };
  },
  computed: {
    memberListGroup() {
      return this.membersMap[this.currentNodeId] || [];
    }
  },
  methods: {
    onNodeChange(val) {
      this.currentNodeId = val.pop();
      this.$nextTick(() => {
        let a = this.$refs.cascader.$el;
        let b = document.getElementById(this.parentId);
        if (a.clientWidth > b.clientWidth) {
          a.offsetParent.style.transform = `translateX(-${a.clientWidth -
            b.clientWidth}px)`;
        } else {
          a.offsetParent.style.transform = `translateX(0px)`;
        }
      });
    },
    searchMember(value) {
      searchMemberDebounce(value, res => {
        if (Util.isAjaxResOk(res)) {
          this.memberListSearch = res.data.data.members;
        }
      });
    },
    selectMember(member) {
      if (this.chosenMap[member.id]) {
        return;
      }
      this.chosenList.push(member);
      this.chosenMap[member.id] = true;
      this.$emit("input", Object.keys(this.chosenMap));
    },
    removeChosen(member, index) {
      this.chosenList.splice(index, 1);
      delete this.chosenMap[member.id];
      this.$emit("input", Object.keys(this.chosenMap));
    }
  }
};
</script>
<style lang="scss" scoped>
.members {
  padding: 0 8px;
  max-width: 600px;
  .member-list {
    padding: 12px 0;
    .chose-list {
      .item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 12px;
        color: #409eff;
        border: 1px solid #409eff;
        margin: 6px;
        border-radius: 4px;
      }
      .item.disabled {
        color: #cccccc;
        border-color: #cccccc;
      }
    }
  }
  .chosen-list.bordered {
    border-bottom: 1px solid #cccccc;
  }
  .chosen-list {
    padding: 12px 0;
    .item {
      position: relative;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 4px 12px;
      color: #409eff;
      border: 1px solid #409eff;
      margin: 6px;
      border-radius: 4px;
      cursor: pointer;
      .el-icon-error {
        position: absolute;
        right: -13px;
        top: -12px;
        font-size: 24px;
        cursor: pointer;
      }
    }
  }
}

.el-cascader-panel {
  border: none;
  margin: 0 8px;
  border-radius: 0;
}
.el-cascader-panel.bordered {
  border-bottom: 1px solid #cccccc;
}
</style>
<style lang="scss">
.member-search {
  border: none;
  padding: 0 12px;
  .el-input__inner {
    border: none;
    border-bottom: 1px solid #cccccc;
    border-radius: 0;
  }
}
</style>