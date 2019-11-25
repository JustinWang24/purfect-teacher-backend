<script>
jQuery(document).ready(function(){
   $R('#content', { lang: 'zh_cn' });
});


$("#url").hide();
$("#image").hide();
$("#image_text").hide();

//给div添加change事件
$("#select").change(function() {

    if ($(this).val() == 0) {

        $("#url").hide();
        $("#image").show();
        $("#image_text").hide();

    } else if($(this).val() == 1) {

        $("#url").hide();
        $("#image").hide();
        $("#image_text").show();

    } else if($(this).val() == 2) {

        $("#image_text").hide();
        $("#image").hide();
        $("#url").show();

    } else if($(this).val() == -1) {
        $("#url").hide();
        $("#image").hide();
        $("#image_text").hide();
    }
});



</script>
