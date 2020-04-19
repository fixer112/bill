@extends('user.layout')
@section('title','Airtime Recharge')
@section('content')
<div class="row">
    {{-- @json(airtimeDiscount(request()->user)) --}}
    <div class="col-10 mx-auto card p-5">
        <form action="{{url()->current()}}" method="POST">
            @csrf
            <x-airtime :dat="airtimeDiscount(request()->user)" />
        </form>
    </div>
</div>
@endsection