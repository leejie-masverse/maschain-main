@extends('main.layouts.app', [
])

@section('page')
    <input hidden id="CSRF_TOKEN" value="{{ csrf_token() }}">
@endsection
