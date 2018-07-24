<?php
$name = isset($name) ? $name : 'image';
$strategy = isset($strategy) ? $strategy : 'default';
?>


<div class="file-uploader file-uploader-image" id="{{ $id or uniqid('uploader_') }}" data-strategy="{{ $strategy }}"  data-items='{!! !empty($image) ? '["'.asset($image).'"]' : '[]' !!}' data-form-name="{{ $name }}" data-filters="{{ json_encode(uploader_strategy($strategy)['filters']) }}">
    <div class="file-uploader-items">
        <button class="file-uploader-picker">选择图片</button>
    </div>
</div>