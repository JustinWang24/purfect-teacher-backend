<!-- Scripts -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/popper/popper.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-blockui/jquery.blockui.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
<!-- bootstrap -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sparkline/jquery.sparkline.js') }}"></script>
<script src="{{ asset('assets/js/pages/sparkline/sparkline-data.js') }}"></script>
<!-- Common js-->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/layout.js') }}"></script>
<script src="{{ asset('assets/js/theme-color.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<!-- material -->
<!-- chart js -->
@if($needChart??false)
    <script src="{{ asset('assets/plugins/chart-js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart-js/utils.js') }}"></script>
    <script src="{{ asset('assets/js/pages/chart/chartjs/home-data.js') }}"></script>
@endif
<!-- summernote -->
@if($needSummerNote??false)
<script src="{{ asset('assets/plugins/summernote/summernote.js') }}"></script>
<script src="{{ asset('assets/js/pages/summernote/summernote-data.js') }}"></script>
@endif
<!-- 漂亮的表单控件 -->
<script src="{{ asset('assets/plugins/select2/js/select2.js') }}"></script>
<script src="{{ asset('assets/js/pages/select2/select2-init.js') }}"></script>
<script src="{{ asset('js/app.js') }}" defer></script>

<!-- dropzone -->
<script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('assets/plugins/dropzone/dropzone-call.js') }}"></script>

<!-- redactor -->
<script src="{{ asset('assets/plugins/redactor/redactor.js') }}"></script>
<script src="{{ asset('assets/plugins/redactor/_langs/zh_cn.js') }}"></script>

@foreach($js as $j)
@include($j)
@endforeach
