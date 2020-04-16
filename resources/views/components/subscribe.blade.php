<script src="{{ asset('js/vue.js')}}"></script>
<script src="{{ asset('js/axios.js')}}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>

<div id="subscribe">

    {{-- <form ref="form"> --}}

    <h2 class="text-6 mb-4"> {{$message}}</h2>

    <div class="form-group">
        <label for="operator">Plan</label>
        <select class="custom-select" id="operator" required="" v-model="amount">
            <option value="">Choose Plan</option>
            @foreach (config("settings.subscriptions") as $key=>$sub)
            <option value="{{$sub['amount']}}"> {{ucfirst($key)}} - {{currencyFormat($sub['amount'])}}</option>
            @endforeach
        </select>
    </div>


    <button class="btn btn-primary btn-block" type="submit" @click="payWithPaystack">Subscribe</button>
    {{--  </form> --}}
</div>
<script>
    new Vue({
    el: '#subscribe',
    data: function() {
    return {
        amount:""
        
        
    }
  }, 
       methods:{
           payWithPaystack(){
              // this.$refs.form.valida();
            if(this.amount=="") return;
            
            var handler = PaystackPop.setup({
              key: '{{env("PAYSTACK_KEY")}}',
              email: '{{$user->email}}',
              amount: this.amount * 100,
              currency: "NGN",
              first_name:'{{$user->fname}}',
              last_name:'{{$user->lname}}',
              //phone:'{{$user->number}}',
              
              //ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
              metadata: {
                  
                  user_id: "{{$user->id}}",
                  reason: 'subscription',
                  upgrade:false,
                 
              },
              
              callback: function(response){
                  window.location.replace("/subscribe/"+response.reference);
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