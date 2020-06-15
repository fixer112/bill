@extends('admin.layout')
@section('title','Mass SMS/APP')
@section('head')

@endsection
@section('js')

@endsection
@section('content') <div class="row">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Send Mass SMS/APP</h4>
        <form method="POST" accept="{{url()->current()}}" id="sms">
            @csrf
            <div class="form-group">
                <label>Subject</label>
                <div class="input-group">
                    <input name="subject" class="form-control @error('subject') is-invalid @enderror"
                        placeholder="Subject">
                    @error('subject')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group @error('content') is-invalid @enderror">
                <label>Message</label>
                <div class="input-group">
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                        required></textarea>
                    @error('content')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group @error('sms') is-invalid @enderror">
                <label>Send to SMS ?</label>
                <div class="input-group">
                    <select name="sms" class="form-control custom-select @error('sms') is-invalid @enderror">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    @error('sms')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Send</button>
        </form>


    </div>
</div>


@endsection