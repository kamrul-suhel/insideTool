@extends('adminlte::page')

@section('title', 'UNILAD Insights')

@section('content_header')
    @yield('content_header')
@stop

@section('content')
    @yield('content')
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    @yield('js')
@stop
