<template>
    <el-autocomplete
            v-model="query"
            style="width: 400px;"
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
        props:[
            'schoolId','scope','tip'
        ],
        computed: {
            'placeholderText': function(){
                return '可输入 教职工/学生姓名'+this.tip+' 进行查找';
            }
        },
        data() {
            return {
                query: '',
            }
        },
        created() {
        },
        methods:{
            querySearchAsync: function (queryString, cb) {
                axios.post(
                    Constants.API.QUICK_SEARCH_USERS_BY_NAME,
                    {query: queryString, school: this.schoolId, scope: this.scope}
                ).then(res => {
                    if(Util.isAjaxResOk(res)){
                        cb(res.data.data);
                    }
                })
            },
            handleSelect: function (item) {
                console.log(item);
            }
        }
    }
</script>

<style scoped>

</style>