@extends('user.layout')
@section('title','Fund Wallet')
@section('head')
<script src="{{ asset('js/vue.js')}}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
@endsection
@section('content')
<div class="row" id="fund">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Fund Wallet</h4>
        <form ref="fund" @submit.prevent="payWithPaystack">
            {{-- @csrf --}}
            <div class="form-group">
                <label>Amount</label>
                <input name="amount" min="{{request()->user->minFund()}}" type="number" step=".01"
                    class="form-control @error('amount') is-invalid @enderror" v-model="amount" required
                    placeholder="Enter Amount">
                @error('amount')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <button class="btn btn-primary btn-block" type="submit">Continue</button>
        </form>
    </div>
</div>
<script>
    new Vue({
    el: '#fund',
    data: function() {
    return {
        amount:"",
    }
  }, 
       methods:{

           payWithPaystack(){
               //console.log(this.$refs.fund);
            //this.$refs.form;
            //return;
           // if(this.amount=="") return;
            
            var handler = PaystackPop.setup({
              key: '{{env("PAYSTACK_KEY")}}',
              email: '{{request()->user->email}}',
              amount: this.amount * 100,
              currency: "NGN",
              first_name:'{{request()->user->fname}}',
              last_name:'{{request()->user->lname}}',
              //phone:'{{-- {{$user->number}} --}}',
              
              //ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
              metadata: {
                  
                  user_id: "{{request()->user->id}}",
                  reason: 'top-up',
                  //upgrade:"{{-- {{$upgrade}} --}}",
                 
              },
              
              callback: function(response){
                  window.location.replace("/wallet/fund/"+response.reference);
                  //alert('success. transaction ref is ' + response.reference);
              },
              onClose: function(){
                  alert('Payment Cancelled');
              }
            });
            handler.openIframe();
          }

        },
        watch:{
            
            },
            created(){

            }
            });
</script>
@endsection