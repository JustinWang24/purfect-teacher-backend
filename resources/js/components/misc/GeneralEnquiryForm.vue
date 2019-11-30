<template>
    <div class="timetable-item-form-wrap">
        <h5>{{ enquiryForm.subTitle }}</h5>
        <el-form ref="generalEnquiryForm" :model="enquiryForm" label-width="80px" class="the-form">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="margin-left:11px;box-shadow:none;padding-right:11px;padding-top:14px;border:none;">
                        <el-form-item label="类型">
                            <el-select v-model="enquiryForm.type" style="width: 50%;">
                                <el-option :label="type" :value="idx" :key="idx" v-for="(type, idx) in types"></el-option>
                            </el-select>
                            <span class="help-text">说明: 请选择是哪一类请求</span>
                        </el-form-item>

                        <el-form-item label="标题">
                            <el-input v-model="enquiryForm.title" style="width: 100%;"></el-input>
                        </el-form-item>

                        <el-form-item label="起始日期">
                            <el-date-picker
                                    v-model="enquiryForm.start_at_date"
                                    type="date"
                                    placeholder="选择日期">
                            </el-date-picker>
                            <span class="help-text">说明: 表示从哪天开始</span>
                        </el-form-item>
                        <el-form-item label="结束日期">
                            <el-date-picker
                                    v-model="enquiryForm.end_at_date"
                                    type="date"
                                    placeholder="选择日期">
                            </el-date-picker>
                            <span class="help-text">结束日期. 如果只有一天, 那么请选择与起始日期相同的日期</span>
                        </el-form-item>

                        <el-form-item label="原因">
                            <el-input type="textarea" v-model="enquiryForm.details"></el-input>
                        </el-form-item>
                    </div>
                </div>
            </div>
        </el-form>
    </div>
</template>
e
<script>
    import { Constants } from '../../common/constants';
    import { Util } from '../../common/utils';

    export default {
        name: "GeneralEnquiryForm",
        props: {
            enquiryForm: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                types:[], // 请求类型
            }
        },
        created() {
            this.types = Constants.ENQUIRY_TYPES;
        }
    }
</script>

<style scoped>

</style>