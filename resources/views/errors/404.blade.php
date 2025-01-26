@extends('errors.layouts.error')

@section('title', __('Not Found'))
@section('image-src', asset('mazer/assets/static/images/samples/error-404.svg'))
@section('message', __('The page you are looking for was not found.'))