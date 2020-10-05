<script src="{{ asset('js/vue.js')}}"></script>
<script src="{{ asset('js/axios.js')}}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<form id="subscribe" @submit.prevent="payWithRave">
    <div>

        {{-- <form ref="form"> --}}

        <h2 class="text-6 mb-4"> {{$message}}</h2>

        <div class="form-group">
            <label for="operator">Plan</label>
            <select class="custom-select" id="operator" required="" v-model="amount">
                <option value="">Choose Plan</option>
                @foreach ($packages as $key=>$sub)
                <option value="{{$sub['amount']}}"> {{ucfirst($key)}} -
                    {{currencyFormat($sub['amount'])}}</option>
                @endforeach
            </select>
        </div>



        <button class="btn btn-primary btn-block" type="submit">Subscribe</button>
        {{--  </form> --}}
    </div>
</form>
<script>
    new Vue({
    el: '#subscribe',
    data: function() {
    return {
        amount:""
        
        
    }
  }, 
       methods:{
           payWithRave() {
               if (!'{{env("ENABLE_ONLINE_PAYMENT")}}') {
                return alert('Payment disabled, please contact us on how to upgrade');
                }
        var x = getpaidSetup({
        PBFPubKey: "{{env('RAVE_PUBLIC_KEY')}}",
        customer_email: '{{request()->user->email}}',
        customer_firstname:'{{request()->user->first_name}}',
        customer_lastname:'{{request()->user->last_name}}',
        custom_title:"{{env('RAVE_TITLE')}}",
        custom_logo:"{{env('RAVE_LOGO')}}",
        custom_description:"Subscription",
        txref: "mw-{{generateRef(request()->user)}}",
        amount: this.amount,
        customer_phone: '{{request()->user->number}}',
        currency: "NGN",
        meta: [{
        metaname: "user_id",
        metavalue: "{{request()->user->id}}"
        },{
        metaname: "reason",
        metavalue: "subscription"
        },{
        metaname: "amount",
        metavalue: this.amount
        },{
        metaname: "upgrade",
        metavalue: "{{$upgrade}}"
        }],
        onclose: function() {
        //alert('Payment Cancelled');
        },
        callback: function(response) {
        var txref = response.data.data.txRef; // collect txRef returned and pass to a server page to complete status check.
        console.log(response.data.data);
        window.location.replace("/verify/subscribe/"+txref);
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
              // this.$refs.form.valida();
            if(this.amount=="") return;
            
            var handler = PaystackPop.setup({
              key: '{{env("PAYSTACK_KEY")}}',
              email: '{{$user->email}}',
              amount: calcCharges(this.amount,0,0) * 100,
              currency: "NGN",
              first_name:'{{$user->fname}}',
              last_name:'{{$user->lname}}',
              //phone:'{{$user->number}}',
              
              //ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
              metadata: {
                  
                  user_id: "{{$user->id}}",
                  reason: 'subscription',
                  upgrade:"{{$upgrade}}",
                  amount:this.amount,
                 
              },
              
              callback: function(response){
                  window.location.replace("/verify/subscribe/"+response.reference);
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