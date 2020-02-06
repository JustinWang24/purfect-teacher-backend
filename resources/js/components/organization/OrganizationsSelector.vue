<template>
  <div class="org-selector-wrap">
      <div v-if="org" class="selectors-holder">
        <h4>部门 <span v-show="loadingOrganizations" style="font-size: 10px;">数据加载中 <i class="el-icon-loading"></i></span></h4>
        <div v-for="(children, idx) in org" :key="idx">
          <span v-for="child in children" :key="child.id">
            <click-to-select
                    v-on:item-clicked="itemClickedHandler"
                    :has-children="child.status"
                    type="org"
                    :obj="child"
                    :level="child.level">
            </click-to-select>
          </span>
          <el-divider></el-divider>
        </div>
      </div>

      <div class="results-holder">
          <h4>已选择</h4>
          <span v-for="(o, idx) in selected.org" :key="o.id">
              <selected-org :org="o" v-on:remove="onSelectedOrgRemoveHandler"></selected-org>
          </span>
          <el-divider></el-divider>
          <el-button type="success" @click="confirmSelections">确认</el-button>
          <el-button type="success" @click="clearAll">清空所有选项</el-button>
      </div>
  </div>
</template>

<script>
    import {Util} from "../../common/utils";
    import {loadOrganizationsByParent} from '../../common/organizations';
    import ClickToSelect from './ClickToSelect';
    import SelectedOrg from './SelectedOrg';

    export default {
        name: "OrganizationsSelector",
        props:['userUuid','schoolId','roles','existedOrganizations'],
        components:{ClickToSelect, SelectedOrg},
        data(){
            return {
                theEventName: 'organizations-selected', // 对外发布的事件名称
                selectedOrganizations:[], // 已经选择的机构
                // 部门
                org:[],
                // 年级与班级
                years:[],
                // 专业
                majors:[],
                // 人员
                users:[],
                //
                toAll: false, // 全选
                // 被选中的
                selected:{
                    org:[],
                    years:[],
                    grades:[],
                    majors:[],
                    users:[],
                },
                currentOrgLevel: 1,
                loadingOrganizations: false,
            }
        },
        watch:{
            'existedOrganizations': function(val){
                this.selected.org = []; // 清空已有的
                let that = this;
                val.forEach(item => {
                    that.add(that.selected.org, item);
                })
            }
        },
        created: function(){
            // 根据传入的用户，加载其可能的全部组织机构
            this.init();
        },
        mounted: function(){
            let that = this;
            this.existedOrganizations.forEach(item => {
                that.add(that.selected.org, item);
            })
        },
        methods:{
            confirmSelections: function(){
                // 把选定的发布出去
                this.$emit(this.theEventName,{data: this.selected});
            },
            clearAll: function(){
                this.selected.org = [];
            },
            _loadData: function(a, b){
                this.loadingOrganizations = true;
                return loadOrganizationsByParent(a, b);
            },
            init: function(){
                this._loadData(null, null)
                    .then(res => {
                        this.loadingOrganizations = false;
                        if(Util.isAjaxResOk(res)){
                            this.org.push(res.data.data.organ);

                        }
                    })
            },
            itemClickedHandler: function(payload){
                switch (payload.type){
                    case 'major':
                        // this.go(this.selected.majors,payload);
                        break;
                    case 'year':
                        // 点击了年级的按钮， 那么表示对整个班级数组的操作. 先不单独考虑年级
                        break;
                    case 'grade':
                        // 点击了班级的按钮. 先不单独考虑班级
                        // this.go(this.selected.grades,payload);
                        break;
                    case 'org':
                        this.go(this.selected.org,payload);
                        break;
                    default:
                        break;
                }
            },
            go: function(target, payload){
                if(payload.action === 'add'){
                    this.add(target, payload.obj);
                }else if(payload.action === 'remove'){
                    this.remove(target, payload.obj);
                }else if(payload.action === 'load'){
                    this._loadData(payload.obj.id, '').then(res => {
                        this.loadingOrganizations = false;
                        if(!Util.isEmpty(this.org[payload.level])){
                            this.org.splice(payload.level);
                        }
                        this.org.push(res.data.data.organ);
                    })
                }
            },
            add: function(target, item){
                const idx = Util.GetItemIndexById(item.id, target);
                if(idx === null){
                    target.push(item);
                }
            },
            remove: function(target, item){
                const idx = Util.GetItemIndexById(item.id, target);
                if(idx !== null){
                    target.splice(idx, 1);
                }
            },
            onSelectedOrgRemoveHandler: function(payload){
                this.remove(this.selected.org, payload.obj);
            },
            getAllGradesByYear: function(year){
                let grades = null;
                this.years.forEach(item => {
                    if(item.year === year){
                        grades = item.grades;
                    }
                });
                return grades;
            },
        }
    }
</script>

<style scoped lang="scss">
.org-selector-wrap{
  width: 100%;
  display: flex;
  .selectors-holder, .results-holder{
    width: 48%;
    margin: 1%;
    padding: 16px;
    border: solid 1px #ccc;
  }
}
</style>