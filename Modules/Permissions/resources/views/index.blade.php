@extends('permissions::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('permissions.name') !!}</p>
@endsection
