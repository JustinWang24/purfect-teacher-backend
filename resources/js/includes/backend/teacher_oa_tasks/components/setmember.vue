<template>
  <div class="form">
    <el-form ref="form" label-width="80px">
      <el-form-item label="搜索">
        <el-input placeholder="请输入" v-model="keywords"></el-input>
      </el-form-item>
      <el-form-item label="部门">
        <el-cascader :props="memberOptions" @expand-change="onNodeChange"></el-cascader>
      </el-form-item>
    </el-form>
    <div class="members">
      <div
        class="chose-title"
        v-if="(memberListSearch.length > 0 && filtBy==='search') || (memberListGroup.length >0 && filtBy === 'group')"
      >可选人员</div>
      <div class="member-list">
        <div class="list-search chose-list" v-show="filtBy==='search'">
          <div
            class="item"
            @click="selectMember(member)"
            :class="{'disabled': chosenMap[member.id] || disabledList.includes(member.id)}"
            v-for="member in memberListSearch"
            :key="member.id"
          >{{member.name}}</div>
        </div>
        <div class="list-org chose-list" v-show="filtBy==='group'">
          <div
            class="item"
            @click="selectMember(member)"
            :class="{'disabled': chosenMap[member.id] || disabledList.includes(member.id)}"
            v-for="member in memberListGroup"
            :key="member.id"
          >{{member.name}}</div>
        </div>
      </div>
      <div class="chose-title" v-if="chosenList.length > 0">已选人员</div>
      <div class="chosen-list" v-if="chosenList.length > 0">
        <div class="item" v-for="(member, index) in chosenList" :key="member.id">
          <span>{{member.name}}</span>
          <i class="el-icon-error" @click="removeChosen(member, index)"></i>
        </div>
      </div>
    </div>
    <div class="btn-box">
      <el-button type="primary" @click="onSubmit">确定</el-button>
    </div>
  </div>
</template>
<script>
import { TaskApi } from "../common/api";
import { Util } from "../../../../common/utils";
import { searchMemberDebounce } from "../common/utils";
export default {
  name: "dispatch-form",
  props: {
    disabledList: {
      type: Array,
      default: Array
    }
  },
  watch: {
    keywords: function(val, nval) {
      searchMemberDebounce(val, res => {
        if (Util.isAjaxResOk(res)) {
          this.memberListSearch = res.data.data.members;
          this.filtBy = "search";
        }
      });
    }
  },
  computed: {
    memberListGroup() {
      return this.membersMap[this.currentNodeId] || [];
    }
  },
  methods: {
    onSubmit() {
      if (!this.chosenList || this.chosenList.length < 1) {
        this.$message.error("请选择指派人");
        return;
      }
      this.$emit(
        "onSelect",
        this.chosenList.map(member => {
          return member.id.toString();
        })
      );
    },
    onNodeChange(val) {
      this.currentNodeId = val.pop();
      this.filtBy = "group";
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
  },
  data() {
    let that = this;
    return {
      currentNodeId: "",
      membersMap: {},
      filtBy: "",
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
      },
      chosenMap: {},
      memberListSearch: [],
      chosenList: [],
      keywords: ""
    };
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

.form {
  display: flex;
  height: 100%;
  flex-direction: column;
  .el-form {
    flex: 1;
    padding: 12px;
  }
  .el-cascader {
    width: 100%;
  }
  .btn-box {
    flex: none;
    padding: 12px;
    text-align: center;
  }
}
</style>
