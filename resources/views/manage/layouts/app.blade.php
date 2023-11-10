<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    @yield('meta')
    <title>{{ $pageTitle }} | {{ config('app.name') }}</title>
    <link rel='icon' href='favicon.ico' type='image/x-icon'/>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="manifest" href="manifest.json">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <link href="{{ asset('themes/metronic/vendors.bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/metronic/style.bundle.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" />
    <link href="{{ asset('manage/app.css') }}" rel="stylesheet">
    <link href="{{ asset('manage/print.css') }}" media="print" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/basic.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.css" />

    <link href='{{ URL::asset('js/fullcalendar/main.css') }}' rel='stylesheet' />
    <link href='{{ URL::asset('js/fullcalendar/custom.css') }}' rel='stylesheet' />
    <script src='{{ URL::asset('js/fullcalendar/main.js') }}'></script>

    @stack('style')
</head>

@php
    $bodyClass = isset($bodyClass) ? (array)$bodyClass : ['
        m--skin-
        m-header--fixed
        m-header--fixed-mobile
        m-aside-left--enabled
        m-aside-left--skin-dark
        m-aside-left--fixed
        m-aside-left--offcanvas
        m-footer--push
        m-aside--offcanvas-default
    '];
    $bodyClass = implode(' ', $bodyClass)
@endphp
<body
    class="{{ $bodyClass }}"
    data-route="{{ Route::currentRouteName() }}"
>
    <section class="m-grid m-grid--hor m-grid--root m-page">
        @yield('page')
    </section>
    <aside id="m_scroll_top" class="m-scroll-top">
        <i class="la la-arrow-up"></i>
    </aside>
    @stack('quick-sidebar')
    <script src="{{ asset('themes/metronic/vendors.bundle.js') }}"></script>
    <script src="{{ asset('themes/metronic/scripts.bundle.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
{{--    <script src="https://cdn.ckeditor.com/ckeditor5/19.1.1/classic/ckeditor.js"></script>--}}
    <script src="{{ asset('utils/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/min/dropzone.min.js"></script>
    <script type="module" src="{{ asset('utils/js/app.js') }}"></script>
    <script type="module" src="{{ asset('manage/app.js') }}"></script>
    <script type="module" src="{{ asset('manage/partner.js') }}"></script>
    <script src="{{ asset('manage/components/quickSidebar.js') }}"></script>
{{--    <script type="module" src="{{ asset('utils/js/app.js') }}?v={{ now()->timestamp }}"></script>--}}
{{--    <script type="module" src="{{ asset('manage/app.js') }}?v={{ now()->timestamp }}"></script>--}}
{{--    <script type="module" src="{{ asset('manage/partner.js') }}?v={{ now()->timestamp }}"></script>--}}
{{--    <script src="{{ asset('manage/components/quickSidebar.js') }}?v={{ now()->timestamp }}"></script>--}}
    @stack('script')
    @yield('scripts')
</body>

</html>
