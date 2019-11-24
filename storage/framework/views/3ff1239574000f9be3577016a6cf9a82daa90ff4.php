<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title><?php echo e(($pageTitle??'') . config('app.name', 'Laravel')); ?></title>

    <link href="<?php echo e(asset('assets/fonts/simple-line-icons/simple-line-icons.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/fonts/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/fonts/material-design-icons/material-icon.css')); ?>" rel="stylesheet" type="text/css" />
    <!--bootstrap -->
    <link href="<?php echo e(asset('assets/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('assets/plugins/summernote/summernote.css')); ?>" rel="stylesheet">
    <!-- Material Design Lite CSS -->
    <!-- inbox style -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/inbox.min.css')); ?>">
    <!-- Theme Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/theme/hover/theme_style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/theme/hover/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/formlayout.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/responsive.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/theme/hover/theme-color.css')); ?>">
    <!-- select 2 -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/select2/css/select2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/select2/css/select2-bootstrap.min.css')); ?>">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <!-- dropzone -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/dropzone/dropzone.css')); ?>">
</head>
<?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/layouts/desktop/head.blade.php ENDPATH**/ ?>