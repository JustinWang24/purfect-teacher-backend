<script language="JavaScript">
    $(document).ready(function(){
        new window.Vue({
            el: '#enrol-note-manager-app',
            data() {
                return {
                    content: '{!! !empty($note->content) ? str_replace(array("\r\n", "\r", "\n"),'',$note->content) : null !!}',
                    recruitment_intro: '{!! !empty($recruitment_intro) ? str_replace(array("\r\n", "\r", "\n"),'',$recruitment_intro) : null !!}',
                    @include('reusable_elements.section.redactor_options_config',['uuid'=>$user->id])
                }
            }
        });
    });
</script>
