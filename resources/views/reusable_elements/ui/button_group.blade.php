<div class="btn-group">
    <button type="button" class="btn btn-{{ $type }} {{ $text }}">{{ $text }}</button>
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-angle-down"></i>
    </button>
    <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(79px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
        @foreach($subs as $item)
            <li><a href="{{ $item['url'] }}" target="_blank">{{ $item['text'] }}</a></li>
        @endforeach
    </ul>
</div>