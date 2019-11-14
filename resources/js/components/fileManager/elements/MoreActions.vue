<template>
    <div class="more-actions">
        <el-dropdown trigger="click">
              <span class="el-dropdown-link">
                <i class="el-icon-more" :style="{color: color}"></i>
              </span>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item><i class="el-icon-edit"></i>重命名</el-dropdown-item>
                <el-dropdown-item><i class="el-icon-download"></i>下载</el-dropdown-item>
                <el-dropdown-item><i class="el-icon-guide"></i>移动到 ...</el-dropdown-item>
                <el-dropdown-item divided><i class="el-icon-delete"></i>删除</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
    </div>
</template>

<script>
    export default {
        name: "MoreActions",
        props:['file','color']
    }
</script>

<style scoped>

</style>