@if(session('msg'))
    <?php $flashMsg = session('msg'); ?>
    <div class="alert alert-{{ $flashMsg['status']=='success' ? 'primary' : 'danger' }}">
        <strong>{{ $flashMsg['content'] }}</strong>
    </div>
@endif