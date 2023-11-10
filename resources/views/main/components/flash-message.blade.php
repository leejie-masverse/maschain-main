@if (session('success'))
{{--    <div class="alert alert-success alert-block sticky-top">--}}
{{--        <button type="button" class="close pt-0" data-dismiss="alert"></button>--}}
{{--        <strong>{{ session('success') }}</strong>--}}
{{--    </div>--}}
    <input id="id_flash_message" data-type="success" hidden value="{{ session('success') }}">
@endif

@if (session('error'))
{{--    <div class="alert alert-danger alert-block sticky-top">--}}
{{--        <button type="button" class="close pt-0" data-dismiss="alert"></button>--}}
{{--        <strong>{{ session('error') }}</strong>--}}
{{--    </div>--}}
    <input id="id_flash_message" data-type="error" hidden value="{{ session('error') }}">
@endif

@if (session('warning'))
{{--    <div class="alert alert-warning alert-block sticky-top">--}}
{{--        <button type="button" class="close pt-0" data-dismiss="alert"></button>--}}
{{--        <strong>{{ session('warning') }}</strong>--}}
{{--    </div>--}}
    <input id="id_flash_message" data-type="warning" hidden value="{{ session('warning') }}">
@endif

@if (session('info'))
{{--    <div class="alert alert-info alert-block sticky-top">--}}
{{--        <button type="button" class="close pt-0" data-dismiss="alert"></button>--}}
{{--        <strong>{{ session('info') }}</strong>--}}
{{--    </div>--}}
    <input id="id_flash_message" data-type="info" hidden value="{{ session('info') }}">
@endif
