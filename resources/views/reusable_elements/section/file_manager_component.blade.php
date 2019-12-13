@php
/**
 * $syncFlag: 表示由哪个 vue 属性来控制文件管理器组件的显示/隐藏, 默认不传入的话, 使用 showFileManagerFlag
 * $pickFileHandler: 表示有文件管理器组件发布的 pick-this-file 事件是由哪个 vue 的方法来监听
 *                  如果没提供, 则文件管理器组件不会提供发布上述事件的功能
 * $allowedFileTypes: 表示允许被选择的文件的类型
 */
@endphp
<el-drawer
        title="我的易云盘"
        :visible.sync="{{ $syncFlag??'showFileManagerFlag' }}"
        direction="rtl"
        size="100%"
        custom-class="e-yun-pan">
    <file-manager
            user-uuid="{{ \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user()->uuid : ($user->uuid ?? null) }}"
            :allowed-file-types="[{!! isset($allowedFileTypes) ? $allowedFileTypes : null !!}]"
            :pick-file="{{ isset($pickFileHandler) ? 'true':'false' }}"
            {!! isset($pickFileHandler) ? 'v-on:pick-this-file="'.$pickFileHandler.'"' : null !!}
    ></file-manager>
</el-drawer>