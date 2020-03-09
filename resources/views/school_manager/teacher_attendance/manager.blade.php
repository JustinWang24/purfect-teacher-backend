列表页
@foreach($attendances as $attendance)
    <p>
    考勤组 {{ $attendance->title }}
    考勤人数 {{ $attendance->members }}
    考勤班次@if ($attendance->using_afternoon) 早中晚 @else 早晚 @endif
    wifi {{ $attendance->wifi_name }}
    操作 {{ $attendance->id }}
    </p>
@endforeach
