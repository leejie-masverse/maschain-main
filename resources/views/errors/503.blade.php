@extends('errors::minimal')

@section('title', __('Service Unavailable'))
{{--@section('code', '503')--}}
@section('message', __($exception->getMessage() ?: 'We are having a regular maintenance to serve you better. Check back in two hours to continue browsing out website. Thanks!'))
