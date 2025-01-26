@extends('errors.layouts.error')

@section('title', __('Forbidden'))
@section('image-src', asset('mazer/assets/static/images/samples/error-403.svg'))
@section('message', __($exception->getMessage() ?: 'Forbidden'))