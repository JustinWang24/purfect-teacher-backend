@php
    $tip = '';
    switch ($highlight){
        case 'campus':
            $tip = '/校区名';
            break;
        case 'institute':
            $tip = '/学院名';
            break;
        case 'department':
            $tip = '/系名';
            break;
        case 'major':
            $tip = '/专业名';
            break;
        case 'grade':
            $tip = '/班级名';
            break;
        case 'room':
            $tip = '/物业名';
            break;
        default:
            break;
    }
@endphp
<div id="user-quick-search-app" class="pull-left mr-4">
        <search-bar
                style="width: 260px;"
                school-id="{{ session('school.id') }}"
                scope="{{ $highlight }}"
                tip="{{ $tip }}"
                v-on:result-item-selected="onItemSelected"
        ></search-bar>
</div>
