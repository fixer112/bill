@extends('user.subscribe.layout')
@section('title','Subscribe to a new package')
@section('content')

<p>Not ready for a reseller account? <a href="/user/{{request()->user->id}}/downgrade">Downgrade to Individual
        Account</a></p>
<x-subscribe :user="request()->user" message="Subscribe to a new package"
    :packages='config("settings.subscriptions")' />
@endsection