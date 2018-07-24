<?php
$name = isset($name) ? $name : 'file';
$strategy = isset($strategy) ? $strategy : 'file';
?>

    <div class="file-uploader file-uploader-file" id="{{ $id or uniqid('uploader_') }}" data-strategy="{{ $strategy }}"  data-items='{!! !empty($file) ? '["'.asset($file).'"]' : '[]' !!}' data-item-template="<div class='file-uploader-item-actions'><a href='{URL}' target='_blank'>查看</a></div>" data-form-name="{{ $name }}" data-filters="{{ json_encode(uploader_strategy($strategy)['filters']) }}">
    <div class="file-uploader-items">
        <button class="file-uploader-picker">选择文件</button>
    </div>
</div>