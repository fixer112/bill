@extends('user.layout')
@section('title','Electricity Bill')
@section('content')
<div class="row">
    <div class="col-10 mx-auto card p-5">
        <form action="{{url()->current()}}" method="POST">
            @csrf
            <x-electricity :discount="electricityDiscount(request()->user)" />
        </form>
    </div>
</div>
@endsection