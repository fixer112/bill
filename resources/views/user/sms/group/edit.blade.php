@extends('user.layout')
@section('title','Edit SMS Group')

@section('content')
<div id="row">
    <div id="col-12">
        @if(env('SMS_ALERT'))
        <div class="alert alert-danger rounded m-3">
            {!! env('SMS_ALERT') !!}
        </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Edit SMS Group</h4>
        <form action="{{url()->current()}}" method="POST" id="compose">
            @csrf
            <div class="form-group">
                <label>Group Name</label>
                <div class="input-group">

                    <input name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Group Name"
                        value="{{request()->group->name}}">
                </div>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone Numbers</label>
                <div class="input-group">
                    <textarea class="form-control @error('numbers') is-invalid @enderror" name="numbers"
                        placeholder="Enter numbers seperated by commas. e.g 08011111111,09011111111">{{request()->group->numbers}}</textarea>
                    @error('numbers')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Continue</button>
        </form>
    </div>
</div>

@endsection