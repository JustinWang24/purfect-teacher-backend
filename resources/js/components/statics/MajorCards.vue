<template>
    <div class="majors-wrap">
        <el-card shadow="always" v-for="(major, idx) in majors" :key="idx">
            <p class="major-name">
                <el-badge v-if="major.hot" value="热门" class="item">
                    {{ major.name }}({{ major.period }}年制)
                </el-badge>
                <span v-else>
                    {{ major.name }}({{ major.period }}年制)
                </span>
            </p>
            <p class="m-desc">
                {{ major.description }}
            </p>
            <el-row>
                <el-col :span="6">
                    <p class="stat">
                        <span class="apply">人数: {{ major.seats }}</span>/<span class="seats">{{ major.seats }}</span>
                    </p>
                </el-col>
                <el-col :span="6">
                    <p class="fee">
                        学费: {{ major.fee }}/年
                    </p>
                </el-col>
                <el-col :span="6">
                    <el-button style="float: right;font-size: 12px;padding: 4px 15px;" size="mini" type="primary" round v-on:click="apply(major)">报名</el-button>
                </el-col>
                <el-col :span="6">
                    <el-button style="float: right;font-size: 12px;padding: 4px 15px;" size="mini" type="success" round v-on:click="loadDetail(major)">详情</el-button>
                </el-col>
            </el-row>
        </el-card>
    </div>
</template>

<script>
    import { loadMajorDetail } from '../../common/registration_form';
    import { Util } from '../../common/utils';
    export default {
        name: "MajorCards",
        props:[
            'majors'
        ],
        data(){
            return {
            }
        },
        methods:{
            loadDetail: function (major) {
                this.$emit('show-major-detail', major);
                // loadMajorDetail(major.id).then(res => {
                //     if(Util.isAjaxResOk(res)){
                //         this.$emit('show-major-detail', res.data.data.plan);
                //     }
                //     else{
                //         this.$alert('无法加载专业: ' + major.name + '的详情', '加载失败', {
                //             confirmButtonText: '确定',
                //             type:'error',
                //             customClass: 'for-mobile-alert'
                //         });
                //     }
                // }).catch(e => {
                //     console.log(e);
                //     this.$alert('服务器忙, 无法加载专业: ' + major.name + '的详情. 请稍候再试!', '加载失败', {
                //         confirmButtonText: '确定',
                //         type:'error',
                //         customClass: 'for-mobile-alert'
                //     });
                // })
            },
            apply: function (major) {
                this.$emit('apply-major', major);
            },
        }
    }
</script>

<style scoped lang="scss">
    $defaultPadding: 15px;
    $borderColor: #F6F6F6;
    $txtColor: #333333;
    .majors-wrap{
        padding: $defaultPadding;
        margin-top: -24px;
        border-top: solid 1px $borderColor;
        padding-top:30px;
        .el-card{
            border: none;
            margin-bottom: $defaultPadding;
            p{
                margin:0;
                line-height: 23px;
                color: $txtColor;
            }
            .major-name{
                font-size: 14px;
                margin-bottom: 10px;
            }
            .m-desc{
                font-size: 12px;
                line-height: 17px;
                margin-bottom: 10px;
            }
            .stat{
                color: #4FA6FE;
                font-size: 12px;
            }
            .fee{
                font-size: 12px;
                text-align: center;
            }
        }
    }
</style>