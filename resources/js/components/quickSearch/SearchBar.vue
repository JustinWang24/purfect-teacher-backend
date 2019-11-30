<template>
    <el-autocomplete
            v-model="query"
            :style="{width: width}"
            :clearable="true"
            :fetch-suggestions="querySearchAsync"
            prefix-icon="el-icon-search"
            :placeholder="placeholderText"
            @select="handleSelect"
    ></el-autocomplete>
</template>

<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';

    export default {
        name: "SearchBar",
        props:{
            schoolId: {
                type: [String, Number],
                require: true
            },
            scope: {
                type: String,
                required: true
            },
            tip:{
                type:String,
                required: false,
                default: ''
            },
            fullTip:{ // 如果传入这个值, 那么搜索框中的说明文字就只有它
                type:String,
                required: false,
                default: ''
            },
            width:{
                type: String,
                required: false,
                default: '400px'
            },
            initQuery:{
                type: String,
                required: false,
                default: ''
            }
        },
        computed: {
            'placeholderText': function(){
                return Util.isEmpty(this.fullTip) ? '可输入 教职工/学生姓名'+this.tip+' 进行查找' : this.fullTip;
            }
        },
        data() {
            return {
                query: '',
            }
        },
        watch:{
            'initQuery': function(newValue, oldValue){
                this.query = newValue;
            }
        },
        created() {
        },
        methods:{
            querySearchAsync: function (queryString, cb) {
                const _queryString = queryString.trim();
                if(Util.isEmpty(_queryString)){
                    // 如果视图搜索空字符串, 那么不执行远程调用, 而是直接回调一个空数组
                    cb([]);
                }
                else{
                    axios.post(
                        Constants.API.QUICK_SEARCH_USERS_BY_NAME,
                        {query: _queryString, school: this.schoolId, scope: this.scope}
                    ).then(res => {
                        if(Util.isAjaxResOk(res)){
                            cb(res.data.data);
                        }
                    })
                }
            },
            handleSelect: function (item) {
                // 把找到的发布出去
                this.$emit('result-item-selected',{item: item})
            }
        }
    }
</script>

<style scoped>

</style>