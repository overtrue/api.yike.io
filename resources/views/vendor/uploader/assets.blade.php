<link rel="stylesheet" type="text/css" href="{{ asset('vendor/uploader/css/uploader.css') }}">
<script src="{{ asset('vendor/uploader/vendor/plupload/js/plupload.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
// Initialize the widget when the DOM is ready
    var uploader_options = {
        // General settings
        runtimes : 'html5,flash,silverlight,html4',
        url : '{{ route('file.upload') }}',

        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,

        // Flash settings
        flash_swf_url : '{{ asset('vendor/uploader/vendor/plupload/js/Moxie.swf') }}',

        // Silverlight settings
        silverlight_xap_url : '{{ asset('vendor/uploader/vendor/plupload/js/Moxie.xap') }}'
    };
</script>
<script src="{{ asset('vendor/uploader/js/uploader.js') }}" type="text/javascript"></script>
