@extends('user.layout')
@section('title','Fund Wallet')
@section('head')
<script src="{{ asset('js/vue.js')}}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
@endsection
@section('content')

@if (Auth::user()->can('fund',request()->user))
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
                    <input name="amount" type="number" step=".01"
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
        <form ref="fund" @submit.prevent="payWithRave" id="paymentForm">

            {{--   @csrf  --}}

            {{--  <input type="hidden" name="payment_method" value="both" /> <!-- Can be card, account, both -->
            <input type="hidden" name="description" value="Beats by Dre. 2017" />
            <!-- Replace the value with your transaction description -->
            <input type="hidden" name="country" value="NG" /> <!-- Replace the value with your transaction country -->
            <input type="hidden" name="currency" value="NGN" />
            <!-- Replace the value with your transaction currency -->
            <input type="hidden" name="email" value="test@test.com" />
            <!-- Replace the value with your customer email -->
            <input type="hidden" name="firstname" value="Oluwole" />
            <!-- Replace the value with your customer firstname -->
            <input type="hidden" name="lastname" value="Adebiyi" />
            <!-- Replace the value with your customer lastname -->
            <input type="hidden" name="metadata" value="{{ json_encode($array) }}">
            <!-- Meta data that might be needed to be passed to the Rave Payment Gateway -->
            <input type="hidden" name="phonenumber" value="090929992892" />
            --}}
            <div class="form-group">
                <label>Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>
                    <input name="amount" min="200" max="{{env('MAX_FUND',2500)}}" type="number" step=".01"
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
           
           payWithRave() {
               if (!'{{env("ENABLE_ONLINE_PAYMENT")}}') {
            return alert('Payment disabled, please make a transfer to {{request()->user->account_number}} ({{request()->user->bank_name}}) to fund your wallet');
            }
               //return;
            var x = getpaidSetup({
            PBFPubKey: "{{env('RAVE_PUBLIC_KEY')}}",
            customer_email: '{{request()->user->email}}',
            customer_firstname:'{{request()->user->first_name}}',
            customer_lastname:'{{request()->user->last_name}}',
            custom_title:"{{env('RAVE_TITLE')}}",
            custom_logo:"{{env('RAVE_LOGO')}}",
            custom_description:"Wallet Funding",
            amount: this.amount,
            customer_phone: '{{request()->user->number}}',
            currency: "NGN",
            txref: "mw-{{generateRef(request()->user)}}",
            meta: [{
            metaname: "user_id",
            metavalue: "{{request()->user->id}}"
            },{
            metaname: "reason",
            metavalue: "top-up"
            },{
            metaname: "amount",
            metavalue: this.amount
            }],
            onclose: function() {
               //alert('Payment Cancelled');
            },
            callback: function(response) {
            var txref = response.data.data.txRef; // collect txRef returned and pass to a server page to complete status check.
            console.log(response.data.data);
            window.location.replace("/verify/wallet/fund/"+txref);
            /*if (
            response.data.chargeResponseCode == "00" ||
            response.data.chargeResponseCode == "0"
            ) {
            // redirect to a success page
            window.location.replace("/verify/wallet/fund/"+txref);
            } else {
            // redirect to a failure page.
            
            }*/
        
            x.close(); // use this to close the modal immediately after payment.
            }
            });
            },

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