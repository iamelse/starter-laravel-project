@extends('errors.layouts.error')

@section('title', __('Server Error'))
@section('image-src', asset('mazer/assets/static/images/samples/error-500.svg'))
@section('message', __('Server Error'))