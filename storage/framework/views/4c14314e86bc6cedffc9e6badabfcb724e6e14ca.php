<!-- Scripts -->
<script src="<?php echo e(asset('assets/plugins/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/popper/popper.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/jquery-blockui/jquery.blockui.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.js')); ?>"></script>
<!-- bootstrap -->
<script src="<?php echo e(asset('assets/plugins/bootstrap/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/sparkline/jquery.sparkline.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/pages/sparkline/sparkline-data.js')); ?>"></script>
<!-- Common js-->
<script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/layout.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/theme-color.js')); ?>"></script>
<!-- material -->
<!-- chart js -->
<?php if($needChart??false): ?>
    <script src="<?php echo e(asset('assets/plugins/chart-js/Chart.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/plugins/chart-js/utils.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/pages/chart/chartjs/home-data.js')); ?>"></script>
<?php endif; ?>
<!-- summernote -->
<?php if($needSummerNote??false): ?>
<script src="<?php echo e(asset('assets/plugins/summernote/summernote.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/pages/summernote/summernote-data.js')); ?>"></script>
<?php endif; ?>
<!-- 漂亮的表单控件 -->
<script src="<?php echo e(asset('assets/plugins/select2/js/select2.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/pages/select2/select2-init.js')); ?>"></script>
<script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

<!-- dropzone -->
<script src="<?php echo e(asset('assets/plugins/dropzone/dropzone.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/dropzone/dropzone-call.js')); ?>"></script>
<?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/layouts/desktop/js.blade.php ENDPATH**/ ?>