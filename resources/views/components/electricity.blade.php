<script src="{{ asset('js/axios.js')}}"></script>
<script src="{{ asset('js/vue.js')}}"></script>

<div id="row">
    <div id="col-12">
        @if(env('ELECTRICITY_ALERT'))
        <div class="alert alert-danger rounded m-3">
            {!! env('ELECTRICITY_ALERT') !!}
        </div>
        @endif
    </div>
</div>

<h4 class="text-6 mb-4">Elecricity</h4>
<div id="electricity">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Service Type</label>
                <select class="custom-select @error('service') is-invalid @enderror" v-model="product" required>
                    <option value="">Select Type</option>
                    @foreach (config("settings.bills.electricity.products") as $key => $bill)
                    <option value='{{json_encode($bill)}}'>{{strtoupper($bill['name'])}}</option>
                    @endforeach
                </select>
                @error('service')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <input v-model="service" name="service" hidden />

            <div class="form-group">
                <label>Amount (Charges of NGN@{{bills['charges'] * multiples}})</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>

                    <input class="form-control @error('amount') is-invalid @enderror" type="number" name="amount"
                        required v-model="amount" :min="min" :max="max" :disabled="service == ''">
                    @error('amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

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
                <label>Meter Type</label>
                <div class="input-group">

                    <select type="text" class="form-control custom-select @error('type') is-invalid @enderror"
                        name="type" required placeholder="Meter Type">
                        <option value="1" {{old('type') == '1' ? 'selected' :''}}>Prepaid</option>
                        <option value="0" {{old('type') == '0' ? 'selected' :''}}>Postpaid</option>
                    </select>
                    @error('type') <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Meter Number</label>
                <div class="input-group">
                    <input class="form-control @error('meter_no') is-invalid @enderror" name="meter_no" type="text"
                        placeholder="Meter number" required v-model="number" required :disabled="service == ''">
                    @error('meter_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Name</label>
                <div class="input-group">
                    <input class="form-control" placeholder="Meter Name" required v-model="name" readonly>

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



        </div>
        <button class="btn btn-primary btn-block" type="submit" :disabled="!submit">Continue</button>
    </div>
</div>

<script>
    var cable = new Vue({
    el: '#electricity',
    data: function() {
    return {
        number:"",
        amount:"",
        name:"",
        service:"",
        product:"",
        min:0,
        max:0,
        discountAmount:"",
        //discount:"{{$discount}}",
        bills:@json(config("settings.bills.electricity")),
        bonus:"{{$discount}}",
        info:"",
        submit:false,
        multiples:1,
        
        
    }
  }, 
       methods:{
         checkname(){
        this.name = "Validating Meter  Number .....";
        this.info = "";
        this.submit = false;
        console.log('/verify/meter_no/'+this.service+'/'+this.number);
        axios.get('/verify/meter_no/'+this.service+'/'+this.number)
        .then(response => {
        console.log(response.data)
        if(response.data.code == 100){
        this.name = response.data.message;
        this.info = response.data;
        this.submit = true;
        console.log(this.info);
        }else if(response.data == ''){
            this.name = "Unable to validate meter number";
            this.submit = false;
        
        }else{
        this.name = 'meter number does not exist';
        this.submit = false;
        }
        
        })
        .catch((error)=>{
            this.name = "Unable to validate meter number";
            this.submit = false;
        console.log(error.response.data)
        })
        },

        },
        watch:{
            product(n){
                var product = JSON.parse(n);
                this.service = product["product_id"];
                this.min = product["min_denomination"];
                this.max = product["max_denomination"];
                console.log(this.service);

            },
            amount(n){
                this.multiples =Math.ceil(this.amount/+("{{env('CABLE_DISCOUNT_MULTIPLE',5000)}}"));
                //console.log(multiples);
                var charges = (+this.bills['charges'] * this.multiples) - ((+this.bonus / 100) * (+this.bills['charges'] * this.multiples));
                this.discountAmount = +this.amount + (+charges);
                //this.details = 'Cable Subscription of ' + this.type.toUpperCase() + '-' + plan["name"] + '- ' + plan["price"] + ' - '+ plan["duration"] +' for smart no '+this.number+' ('+this.mobile_number+')';
            },
            number(n){
                if(n.length >= 10){
                    this.checkname();
                }

            }

            
           
            
            },
            created(){
               
            }
            });

</script>