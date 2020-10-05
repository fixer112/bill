@extends('admin.layout')
@section('title','Mass Mail')
@section('head')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
{{--  <script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>  --}}
<script src="{{ asset('js/vue.js')}}"></script>
@endsection
@section('js')
<script>
    tinymce.init({
        selector:'#editor',
        width: 900,
        height: 300
    });
</script>
@endsection
@section('content') <div class="row">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Send Mass Mail</h4>
        <form method="POST" accept="{{url()->current()}}" id="mail">
            @csrf
            <div class="form-group">
                <label>Subject</label>
                <div class="input-group">
                    <input name="subject" class="form-control @error('subject') is-invalid @enderror" required
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
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" id="editor"
                        required></textarea>
                    @error('content')
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