@extends('user.layout')
@section('title','Upgrade to a new package')
@section('content')
<div class="row">
    {{-- {{json_encode(request()->user->upgradeList())}} --}}
    <div class="col-10 mx-auto card p-5">
        <x-subscribe :user="request()->user" message="Upgrade to a new package"
            :packages="request()->user->upgradeList()" :upgrade="$isUpgrade" />
    </div>
    @endsection