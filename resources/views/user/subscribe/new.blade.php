@extends('user.subscribe.layout')
@section('title','Subscribe to a new package')
@section('content')
<x-subscribe :user="request()->user" message="Subscribe to a new package"
    :packages='config("settings.subscriptions")' />
@endsection