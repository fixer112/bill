<script src="{{ asset('js/axios.js')}}"></script>
<script src="{{ asset('js/vue.js')}}"></script>

<div id="row">
    <div id="col-12">
        @if(env('CABLE_ALERT'))
        <div class="alert alert-danger rounded m-3">
            {!! env('CABLE_ALERT') !!}
        </div>
        @endif
    </div>
</div>

<h4 class="text-6 mb-4">Cable Tv</h4>
<div id="cable">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Type</label>
                <select class="custom-select @error('type') is-invalid @enderror" name="type" required v-model="type">
                    <option value="">Select Type</option>
                    @foreach ($dat as $key =>$discount)
                    <option value='{{$key}}'>{{strtoupper($key)}}</option>
                    @endforeach
                </select>
                @error('type')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            {{--  <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" placeholder="Enter Email Address">
        <div id="error" class="is-invalid text-danger"></div>
       
    </div> --}}

            <div class="form-group">
                <label>Plan</label>
                <select class="custom-select @error('plan') is-invalid @enderror" required v-model="plan"
                    :disabled="plans == ''">
                    <option value="">Select Plan</option>
                    <template v-for="p,key in plans">
                        <option :value="key">@{{p['name'].toUpperCase()}} - @{{p['price'] + p['charges']}} -
                            @{{p['duration']}}</option>
                    </template>
                    {{--  @foreach ($dat as $key => $discount )
                    <option value='{{$key}}'>{{strtoupper($key)}}</option>
                    @endforeach --}}
                </select>
                @error('plan')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <input name="amount" readonly required hidden v-model="amount" />

            <div class="form-group">
                <label>Charges Discount at @{{bonus}}%</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>

                    <input class="form-control @error('discount_amount') is-invalid @enderror" type="number"
                        name="discount_amount" required v-model="discountAmount" readonly>
                    @error('discount_amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Smart Card Number</label>
                <div class="input-group">
                    <input class="form-control @error('smart_no') is-invalid @enderror" name="smart_no" type="text"
                        placeholder="Smart card number" required v-model="number" required>
                    @error('smart_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Name</label>
                <div class="input-group">
                    <input class="form-control" placeholder="Smart card name" required v-model="name"
                        name="customer_name" required readonly>

                </div>
            </div>

            @if(!$guest)
            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-group">
                    <input class="form-control @error('password') is-invalid @enderror" name="password" type="password"
                        placeholder="Confirm your password to continue" required>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            @endif
            <input name="customer_number" v-model="cus_number" hidden />
            <input name="invoice_no" v-model="invoice" hidden />


        </div>
        <button class="btn btn-primary btn-block" type="submit" :disabled="!submit">Continue</button>
    </div>
</div>

<script>
    var cable = new Vue({
    el: '#cable',
    data: function() {
    return {
        number:"",
        amount:"",
        name:"",
        type:"",
        plans:"",
        plan:"",
        discountAmount:"",
        data:@json($dat),
        bills:@json(getCable()),
        bonus:0,
        info:"",
        cus_number:"",
        invoice:"",
        submit:false,
        
        
    }
  }, 
       methods:{
         checkname(){
        this.name = "Validating SmartCard  Number .....";
        this.info = "";
        this.submit = false;
        console.log(this.bank_code);
        axios.get('/verify/smart_no/'+this.type+'/'+this.number)
        .then(response => {
        console.log(response.data)
        if(response.data.customerName){
        this.name = response.data.customerName;
        this.info = response.data;
        this.cus_number = response.data.customerNumber;
        this.invoice = response.data.invoice;
        this.submit = true;
        console.log(this.info);
        }else if(response.data == ''){
            this.name = "Unable to validate smartcard";
        
        }else{
        this.name = 'SmartCard number does not exist';
        }
        
        })
        .catch((error)=>{
        console.log(error.response.data)
        })
        },

        },
        watch:{

            type(n){
                //console.log(n);
                this.name = "";
                this.number = "";
                this.plans = this.bills[n];
                this.bonus = this.data[n];
                
                //console.log(this.plans);
            },
            plan(n){
                var plan = this.bills[this.type][n];
                this.amount = this.bills[this.type][n]['amount'];
                var charges = plan['charges'] - ((this.bonus / 100) * plan['charges']) ;
                this.discountAmount = this.bills[this.type][n]['price'] + charges;
                this.details = 'Cable Subscription of ' + this.type.toUpperCase() + '-' + plan["name"] + '- ' + plan["price"] + ' - '+ plan["duration"] +' for smart no '+this.number;
            },
            number(n){
                console.log(this.type);
                if(n.length >= 10){
                    this.checkname();
                }

            }

            
           
            
            },
            created(){
                //console.log(this.bills);
            }
            });

</script>