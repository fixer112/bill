@extends('user.layout')
@section('title','Api Documentation')
@section('content')
<div class="row">
    <div class="col-10 mx-auto card p-5">
        <div class="mb-5">
            <a href="/user/{{request()->user->id}}/api/reset"> <button class="btn btn-primary">RESET API
                    KEY</button></a>
        </div>
        <div class="">
            <p><strong>API KEY</strong> : <i>{{request()->user->api_token}}</i></p>
            <p><strong>User ID</strong> : <i>{{request()->user->id}}</i></p>
            Read full api documentation <a href="https://documenter.getpostman.com/view/4721383/Szf9UmbT"> here</a>
        </div>
    </div>
</div>
@endsection