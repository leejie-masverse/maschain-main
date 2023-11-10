<!DOCTYPE html>
<html lang="en">
@php
    $pageTitle = isset($pageTitle) && $pageTitle !== null ? $pageTitle : '';
    $ogImage = isset($ogImage) && $ogImage !== null ? $ogImage : '';
    $ogTitle = isset($ogTitle) && $ogTitle !== null ? $ogTitle : '';
    $ogDescription = isset($ogDescription) && $ogDescription !== null ? $ogDescription : '';
@endphp

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta property="og:title" content="{{ $ogTitle }}"/>
    <meta property="og:description" content="{{ $ogDescription }}"/>
    <meta property="og:url" content="{{ Request::url() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="{{ $ogImage }}"/>
    <meta property="og:image:secure" content="{{ $ogImage }}"/>
    <title>{{ $pageTitle }} | {{ config('app.name') }}</title>

    <link rel='icon' href='favicon.ico' type='image/x-icon'/>
    <link rel="manifest" href="manifest.json">
    <link href="{{ asset('css/vendor/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
{{--    <link href="{{ asset('themes/metronic/vendors.bundle.css') }}" rel="stylesheet">--}}
{{--    <link href="{{ asset('themes/metronic/style.bundle.css') }}" rel="stylesheet">--}}
{{--    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">--}}
{{--    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />--}}
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" />--}}
{{--    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />--}}
{{--    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" />--}}
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
{{--    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">--}}
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" crossorigin="anonymous">--}}
{{--    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">--}}
    <link href="{{ asset('main/css/app.css') }}" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png')}}">
    @stack('style')
</head>

<body
    data-route="{{ Route::currentRouteName() }}"
>
    <section id="main_section" class="m-page">
        @include('main.layouts.header')

        @include('main.components.flash-message')

        @yield('page')
    </section>

    {{--@include('main.components.live-chat')--}}
    @include('main.layouts.footer')


    <aside id="m_scroll_top" class="m-scroll-top">
        <i class="la la-arrow-up"></i>
    </aside>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.0-alpha.21/swiper-bundle.min.js"
            integrity="sha512-tvodlm76/5FmjtTQ66p35nUaJbgLpjsFWjAYKJsvrTqa6ajMEp3bJqbvwl77GPPg/SiR8K5ogtym3Qgi+Z0uYg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset("js/vendor/bootstrap.min.js") }}"></script>

{{--    <script src="{{ asset('themes/metronic/vendors.bundle.js') }}"></script>--}}
{{--    <script src="{{ asset('themes/metronic/scripts.bundle.js') }}"></script>--}}
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>--}}
{{--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>--}}
{{--    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>--}}
{{--    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>--}}
{{--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
{{--    <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>--}}
    @stack('prescript')
{{--    <script type="module" src="{{ asset('utils/lazysizes.min.js') }}"></script>--}}
    <script type="module" src="{{ asset('main/js/app.js') }}"></script>
    @stack('script')
    @yield('scripts')

</body>

</html>
