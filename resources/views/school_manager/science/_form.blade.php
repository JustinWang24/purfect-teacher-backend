@csrf
<div class="col-3">
    <div class="form-group">
        <label for="science-title-input">科研成果</label>
        <input required type="text" class="form-control" id="science-title-input" value="{{$science->title ?? old('title')}}" placeholder="请输入主题名称" name="science[title]">
    </div>
</div>
<div class="form-group">
    <label for="science-content-input">内容</label>
    <textarea name="science[content]" id="content" cols="30" rows="10">{{$science->content ?? old('content')}}</textarea>
</div>
<div>
    <label for="science-title-input">封面图</label>
   {{--media_id--}}
</div>

<script>
    jQuery(document).ready(function(){
           $R('#content', { lang: 'zh_cn' });
        });
</script>
