<script src="{{ asset('js/vue.js')}}"></script>

<div id="row">
    <div id="col-12">
        @if(env('AIRTIME_ALERT'))
        <div class="alert alert-danger rounded m-3">
            {!! env('AIRTIME_ALERT') !!}
        </div>
        @endif
    </div>
</div>
<h4 class="text-6 mb-4">Airtime Recharge</h4>
<div id="airtime">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="mobileNumber">Mobile Number</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+234</span>
                    </div>
                    <input type="text" class="form-control @error('number') is-invalid @enderror" name="number" required
                        placeholder="Enter Mobile Number" v-model="number" data-toggle="tooltip"
                        title="Please make sure you input a valid mobile number.">
                    <div id="error" class="is-invalid text-danger"></div>
                    @error('number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            {{--  <div class="form-group">
        <label>Email</label>
        <input type="text" class="form-control" placeholder="Enter Email Address">
        <div id="error" class="is-invalid text-danger"></div>
       
    </div> --}}

            <div class="form-group">
                <label>Network</label>
                <select class="custom-select @error('network') is-invalid @enderror" name="network" required
                    v-model="network">
                    <option value="">Select Network</option>
                    @foreach ($dat as $key => $discount )
                    <option value='{{$key}}'>{{strtoupper($key)}}</option>
                    @endforeach
                </select>
                @error('network')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <input name="network_code" v-model="network_code" required readonly hidden />
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="amount">Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>

                    <input class="form-control @error('amount') is-invalid @enderror" type="number" name="amount"
                        placeholder="Enter Amount" required v-model="amount" :min="min" :max="max"
                        :disabled="network==''">
                    @error('amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="amount">Discount Amount at @{{bonus}}%</label>
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
        <button class="btn btn-primary btn-block" type="submit">Continue</button>
    </div>
</div>

<script>
    var airtime = new Vue({
    el: '#airtime',
    data: function() {
    return {
        number:"",
        amount:"",
        discountAmount:"",
        data:@json($dat),
        network:"",
        min:"",
        max:"",
        network_code:"",
        networks:@json(config("settings.mobile_networks")),
        bills:@json(config("settings.bills.airtime")),
        bonus:0,
        
        
    }
  }, 
       methods:{
         

        },
        watch:{
            
            amount(n){
                //console.log(this.data[this.network]);
                if(this.network!=''){
                    this.discountAmount = n - (this.data[this.network] / 100) * n;
                }
            },
            network(n){
                console.log(n);
                this.network_code = this.networks[n];
                this.min = this.bills[n]['min'];
                this.max = this.bills[n]['max'];
                this.bonus = this.data[n];

                if(this.amount !=''){
                    this.discountAmount = this.amount - (this.data[n] / 100) * this.amount;
                }
            }
            
            },
            created(){
                //console.log('test');
            }
            });

</script>