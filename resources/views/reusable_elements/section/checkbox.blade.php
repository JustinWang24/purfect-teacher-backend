<div class="card box">
    <div class="card-head">
        <header>{{ trans('permission.'.$item) }}</header>
    </div>
    <div class="card-body" id="checkbox-{{ $item.$idx }}">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @foreach($values as $key=>$value)
                <div class="checkbox checkbox-icon-aqua">
                    <input name="{{$item}}[{{$value}}][]" id="checkbox-{{ $item.$idx.'-'.$key }}" type="checkbox" {{ isset($origin[$item]) ? 'checked' : null }}>
                    <label for="checkbox-{{ $item.$idx.'-'.$key }}">
                        {{ trans('permission.'.$value) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>