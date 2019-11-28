<script>
jQuery(document).ready(function(){
   $R('#content', { lang: 'zh_cn' });
});


$("#file").hide();
$("#note").hide();
$("#image").hide();
$("#inspect_id").hide();
$("#release_time").hide();

//给div添加change事件
$("#select").change(function() {

    if ($(this).val() == 1) {

        $("#file").show();
        $("#note").hide();
        $("#image").hide();
        $("#inspect_id").hide();
        $("#release_time").show();

    } else if($(this).val() == 2) {

        $("#file").show();
        $("#note").show();
        $("#image").show();
        $("#inspect_id").hide();
        $("#release_time").hide();

    } else if($(this).val() == 3) {

        $("#file").show();
        $("#note").hide();
        $("#image").show();
        $("#inspect_id").show();
        $("#release_time").hide();

    } else if($(this).val() == -1) {
        $("#file").hide();
        $("#note").hide();
        $("#image").hide();
        $("#inspect_id").hide();
        $("#release_time").hide();
    }
});



</script>
