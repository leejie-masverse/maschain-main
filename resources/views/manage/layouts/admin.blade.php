@extends('manage.layouts.app', [
    'pageTitle' => $pageTitle,
    'bodyClass' => '
        m-page--fluid
        m--skin-
        m-content--skin-light2
        m-header--fixed
        m-header--fixed-mobile
        m-aside-left--enabled
        m-aside-left--skin-dark
        m-aside-left--fixed
        m-aside-left--offcanvas
        m-footer--push
        m-aside--offcanvas-default
    ',
])

@section('page')
    @php
        $pageSubtitle = $pageSubtitle ?? false;
    @endphp
    <section class="m-grid m-grid--hor m-grid--root m-page">
        @include('manage.layouts.admin-header')
        <main class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            @include('manage.layouts.admin-aside')
            <section class="m-grid__item m-grid__item--fluid m-wrapper">
                <header class="m-subheader">
                    <div class="d-flex align-items-center">
                        <h3 class="m-subheader__title mr-auto">
                            {{ $pageTitle }}
                            @if ($pageSubtitle)
                                <small class="d-block text-muted">{{ $pageSubtitle }}</small>
                            @endif
                        </h3>
                    </div>
                </header>
                <div class="m-content">
                    @include('flash::message')
                    @yield('content')
                </div>
            </section>
        </main>
        @include('manage.layouts.admin-footer')
    </section>
@endsection
