@extends('user.layout')
@section('title','Data Subscription')
@section('content')
<div class="row">
    <div class="col-10 mx-auto card p-5">
        <form action="{{url()->current()}}" method="POST">
            @csrf
            <x-data :dat="dataDiscount(request()->user)" />
        </form>
    </div>
</div>
@endsection