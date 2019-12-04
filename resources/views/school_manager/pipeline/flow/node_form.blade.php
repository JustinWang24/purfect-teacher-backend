<el-row>
    <el-col :span="15">
        <el-form-item label="部门">
            <el-cascader style="width: 90%;" :props="props" v-model="node.organizations"></el-cascader>
        </el-form-item>
        <el-form-item label="部门角色">
            <el-checkbox-group v-model="node.titles">
                <el-checkbox label="{{ \App\Utils\Misc\Contracts\Title::ALL_TXT }}"></el-checkbox>
                @foreach(\App\Models\Schools\Organization::AllTitles() as $title)
                    <el-checkbox label="{{ $title }}"></el-checkbox>
                @endforeach
            </el-checkbox-group>
        </el-form-item>
    </el-col>
    <el-col :span="1">
        <p class="text-center text-info" style="margin-top: 50px;">或</p>
    </el-col>
    <el-col :span="8">
        <el-form-item label="目标用户">
            <el-checkbox-group v-model="node.handlers">
                <el-checkbox label="教师"></el-checkbox>
                <el-checkbox label="职工"></el-checkbox>
                <br>
                <el-checkbox label="学生"></el-checkbox>
            </el-checkbox-group>
        </el-form-item>
    </el-col>
</el-row>

<el-form-item label="说明">
    <el-input type="textarea" placeholder="必填: 例如您可以详细描述, 如果要发起本流程, 需要具备的条件, 可能需要提交的文档等" rows="6" v-model="node.description"></el-input>
</el-form-item>