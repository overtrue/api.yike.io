<?php
$name = isset($name) ? $name : 'avatar';
$strategy = isset($strategy) ? $strategy : 'avatar';
?>

<div class="file-uploader file-uploader-avatar" id="{{ $id or uniqid('uploader_') }}" data-strategy="{{ $strategy}}"  data-items='{!! !empty($avatar) ? '["'.asset($avatar).'"]' : '[]' !!}' data-form-name="{{ $name }}" data-filters="{{ json_encode(uploader_strategy($strategy)['filters']) }}">
    <div class="file-uploader-items">
        <button class="file-uploader-picker">选择图片</button>
    </div>
</div>