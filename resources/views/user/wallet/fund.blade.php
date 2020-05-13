@extends('user.layout')
@section('title','Fund Wallet')
@section('head')
<script src="{{ asset('js/vue.js')}}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
@endsection
@section('content')

@if (Auth::user()->is_admin)
<div class="row" id="fund">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Fund Wallet by Admin</h4>
        <div class="alert alert-success">
            <p>
                You can also fund your wallet by transfering to the account details below, the account is unique to your
                wallet.
                <br>
                Transfer charges of {{env("MONIFY_FEE",0.5)}}% applies.
                <br><br>
                Your wallet will be funded automatically within 5 mins of transfer.
            </p>
            <p>
                <h4 class="text-danger">Account Details</h4>
                <b>Bank:</b> <i>{{request()->user->bank_name}}</i>
                <br>
                <b>Account Name:</b> <i>MoniWallet-{{request()->user->full_name}} (Name used during registration)</i>
                <br>
                <b>Account Number:</b> <i>{{request()->user->account_number}}</i>


            </p>
        </div>
        <form method="POST" accept="{{url()->current()}}">
            @csrf
            <div class="form-group">
                <label>Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>
                    <input name="amount" type="number" step=".01" min="{{request()->user->minFund()}}" max="100000"
                        class="form-control @error('amount') is-invalid @enderror" required placeholder="Enter Amount">
                    @error('amount')
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
@else
<div class="row" id="fund">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Fund Wallet</h4>
        <div class="alert alert-success">
            <p>
                You can also fund your wallet by transfering to the account details below, the account is unique to your
                wallet. <br>
                Transfer charges of {{env("MONIFY_FEE",0.5)}}% applies.
                <br><br>
                Your wallet will be funded automatically within 5 mins of transfer.
            </p>
            <p>
                <h4 class="text-danger">Account Details</h4>
                <b>Bank:</b> <i>{{request()->user->bank_name}}</i>
                <br>
                <b>Account Name:</b> <i>MoniWallet-{{request()->user->full_name}} (Name used during registration)</i>
                <br>
                <b>Account Number:</b> <i>{{request()->user->account_number}}</i>


            </p>
        </div>
        <form ref="fund" @submit.prevent="payWithPaystack">
            {{-- @csrf --}}
            <div class="form-group">
                <label>Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>
                    <input name="amount" min="{{request()->user->minFund()}}" max="10000" type="number" step=".01"
                        class="form-control" v-model="amount" required placeholder="Enter Amount">
                </div>

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

               //return console.log(calcCharges(this.amount ));
            //this.$refs.form;
            //return;
           // if(this.amount=="") return;
            
            var handler = PaystackPop.setup({
              key: '{{env("PAYSTACK_KEY")}}',
              email: '{{request()->user->email}}',
              amount: calcCharges(this.amount ) * 100,
              currency: "NGN",
              first_name:'{{request()->user->fname}}',
              last_name:'{{request()->user->lname}}',
              phone:'{{request()->user->number}}',
              
              //ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
              metadata: {
                  
                  user_id: "{{request()->user->id}}",
                  reason: 'top-up',
                  //upgrade:"{{-- {{$upgrade}} --}}",
                  amount:this.amount,
                 
              },
              
              callback: function(response){
                  window.location.replace("/verify/wallet/fund/"+response.reference);
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
@endif

@endsection