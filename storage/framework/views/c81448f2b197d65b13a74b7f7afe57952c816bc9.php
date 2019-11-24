<?php if(session('msg')): ?>
    <?php $flashMsg = session('msg'); ?>
    <div class="alert alert-<?php echo e($flashMsg['status']=='success' ? 'primary' : 'danger'); ?>">
        <strong><?php echo e($flashMsg['content']); ?></strong>
    </div>
<?php endif; ?><?php /**PATH D:\wamp64\www\etx-t\purfect-teacher-backend\resources\views/reusable_elements/section/session_flash_msg.blade.php ENDPATH**/ ?>