<div class="btn-group">
    <button data-toggle="dropdown" class="btn btn-{{ $type }} dropdown-toggle m-r-20" type="button" aria-expanded="false">
        {{ $text }} <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(79px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
        @foreach($subs as $item)
            @if(is_array($item))
                <li><a href="{{ $item['url'] }}" target="_blank">{{ $item['text'] }}</a></li>
            @else
                <li class="divider"></li>
            @endif
        @endforeach
    </ul>
</div>