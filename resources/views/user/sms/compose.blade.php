@extends('user.layout')
@section('title','Compose New SMS')
@section('head')
<script src="{{ asset('js/vue.js')}}"></script>
@endsection
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
    <div class="col-12 mx-auto card p-5">
        <h4 class="text-6 mb-4">Compose New Sms</h4>
        <form action="{{url()->current()}}" method="POST" id="compose">
            @csrf
            <div class="form-group">
                <label>Sender</label>
                <div class="input-group">

                    <input name="sender" class="form-control @error('sender') is-invalid @enderror"
                        placeholder="Sender">
                </div>
                @error('sender')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone Numbers</label>
                <div class="input-group">
                    <textarea class="form-control @error('numbers') is-invalid @enderror" name="numbers"
                        placeholder="Enter numbers seperated by commas. e.g 08011111111,09011111111"></textarea>
                    @error('numbers')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Group</label>
                <div class="input-group">
                    <select name="group" class="form-control custom-select @error('group') is-invalid @enderror">
                        <option value="">Choose</option>
                        @foreach (request()->user->sms_groups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('group')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label>Route Type</label>
                <div class="input-group">
                    <select name="route" class="form-control custom-select @error('route') is-invalid @enderror"
                        required>
                        <option value="">Choose</option>
                        <option value="2">DND Excluded</option>
                        <option value="3">DND Included</option>
                    </select>
                </div>
                @error('route')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label>Message</label>
                <div class="input-group">
                    <textarea class="form-control @error('message') is-invalid @enderror" name="message"
                        placeholder="Use double space to enter a new line" v-model="message" required></textarea>
                    @error('message')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    @error('minimum_amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-group">
                        <input class="form-control @error('password') is-invalid @enderror" name="password"
                            type="password" placeholder="Confirm your password to continue" required>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>
                </div>
                <br>
                <span>Length:@{{len}}</span><br>
                <span>Pages:@{{pages}}</span><br>
                <span>{{env("SMS_PER_PAGE",160)}} characters per page</span><br>
                <span>Price at {{currencyFormat(smsDiscount(request()->user))}}/page/number</span><br>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Continue</button>
        </form>
    </div>
</div>
<script>
    new Vue({
    el: '#compose',
    data: function() {
    return {
        message:"",
        len:0,
        pages:1,
        cost:2
    }
  }, 
       methods:{

        },
        watch:{
            message(n){
                this.len = n.length;
                var cost = parseFloat("{{env('SMS_PER_PAGE',160)}}");
                this.pages = Math.ceil(this.len/cost);
                //this.cost = this.pages * 2;

            }
            
            },
            created(){

            }
            });
</script>
@endsection