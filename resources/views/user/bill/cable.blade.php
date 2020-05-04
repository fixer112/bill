@extends('user.layout')
@section('title','Cable TV')
@section('content')
<div class="row">
    {{-- @json(airtimeDiscount(request()->user)) --}}
    <div class="col-10 mx-auto card p-5">
        <form action="{{url()->current()}}" method="POST" id="cable-form">
            @csrf
            <x-cable :dat="cableDiscount(request()->user)" />
        </form>
    </div>
</div>
{{--  <script>
    document.getElementById('cable-form').addEventListener("submit",function(event){
        event.preventDefault();
        
    });
</script>  --}}
@endsection