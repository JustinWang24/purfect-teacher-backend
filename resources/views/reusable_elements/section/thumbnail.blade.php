<div class="page-bar">
    <div class="page-title-breadcrumb">
        <div class=" pull-left">
            <div class="page-title">{{ $pageTitle }}</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
            @foreach($words as $key=>$word)
            <li>
                @if($key===0)
                <i class="fa fa-home"></i>&nbsp;
                <a class="parent-item" href="{{ route('home') }}">首页</a>&nbsp;
                @else
                    {{ trans('thumbnail.'.$word) }}
                @endif
                @if($key!==count($words)-1)
                <i class="fa fa-angle-right"></i>
                @endif
            </li>
            @endforeach
        </ol>
    </div>
</div>