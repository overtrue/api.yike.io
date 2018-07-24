<?php
$name = isset($name) ? $name : 'images';
$strategy = isset($strategy) ? $strategy : 'default';
?>
<div class="file-uploader file-uploader-images" id="{{ $id or uniqid('uploader_') }}" data-max-items="{{ $max or 9999 }}" data-strategy="{{ $strategy }}" data-form-name="{{ $name }}" data-items='{!! json_encode(!empty($images) ? array_map('asset', (array)$images) : []) !!}' data-item-template="<img src='{URL}' />" multiple="multiple" data-filters="{{ json_encode(uploader_strategy($strategy)['filters']) }}">
    <div class="file-uploader-items">
        <button class="file-uploader-picker">+</button>
    </div>
</div>