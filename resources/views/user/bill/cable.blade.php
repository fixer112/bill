@extends('user.layout')
@section('title','Cable TV')
@section('content')
<div class="row">
    {{-- @json(airtimeDiscount(request()->user)) --}}
    <div class="col-10 mx-auto card p-5">
        <form action="{{url()->current()}}" method="POST">
            @csrf
            <x-cable :dat="cableDiscount(request()->user)" />
        </form>
    </div>
</div>
@endsection