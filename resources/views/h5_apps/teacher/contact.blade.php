@extends('layouts.h5_teacher_app')
@section('content')
    <div id="app-init-data-holder" data-school="{{ $school->uuid }}" data-id="{{ $school->id }}"></div>
    <div id="school-contacts-list-app" class="school-intro-container">
        <div class="main p-15">
            <h2 class="title">
                {{ $pageTitle }}
            </h2>
            <el-table
                    :data="contacts"
                    empty-text="数据加载中 ..."
                    stripe
                    style="width: 100%">
                <el-table-column
                        prop="name"
                        label="机构名称">
                </el-table-column>
                <el-table-column
                        prop="tel"
                        label="电话号码">
                    <template slot-scope="scope">
                        <a style="color: #007bff; text-decoration: none;" :href="'tel:' + scope.row.tel"><i class="el-icon-phone"></i>&nbsp;@{{ scope.row.tel }}</a>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
@endsection
