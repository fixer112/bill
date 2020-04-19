<script src="{{ asset('js/vue.js')}}"></script>


<h4 class="text-6 mb-4">Data Subscription </h4>
<div id="airtime">
    <div class="form-group">
        <label for="mobileNumber">Mobile Number</label>
        <input type="text" id="phone" class="form-control @error('number') is-invalid @enderror" name="number" required
            placeholder="Enter Mobile Number">
        <div id="error" class="is-invalid text-danger"></div>
        @error('number')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label>Network</label>
        <select class="custom-select @error('network') is-invalid @enderror" name="network" required v-model="network">
            <option value="">Select Network</option>
            @foreach ($dat as $key => $value )
            <option value='{{$key}}'>{{strtoupper($key)}} {{-- {{getLastString($value[0]['id'])}} --}}</option>
            @endforeach
        </select>
        @error('network')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group" v-if="network!=''">
        <label>Data Plan</label>
        <select class="custom-select @error('network') is-invalid @enderror" required v-model="plan">
            <option value="">Select Plan</option>
            <template v-for="data,key in plans">
                <option :value='key'>@{{this.getLastString(data["id"])}} - @{{data["topup_currency"]}}
                    @{{data["price"]}}
                    {{-- {{getLastString($value[0]['id'])}} --}}
                </option>

            </template>

        </select>
        @error('network')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <input name="network_code" v-model="network_code" required readonly hidden />

    <input name="amount" v-model="amount" required readonly hidden />
    <input name="details" v-model="details" required readonly hidden />


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
    <button class="btn btn-primary btn-block" type="submit">Continue</button>
</div>

<script>
    new Vue({
    el: '#airtime',
    data: function() {
    return {
        amount:"",
        discountAmount:"",
        data:@json($dat),
        network:"",
        network_code:"",
        networks:@json(config("settings.mobile_networks")),
        bills:@json(config("settings.bills.data")),
        bonus:0,
        plans:"",
        plan:"",
        details:"",
        
    }
  }, 
       methods:{
           
         

        },
        watch:{

            plan(n){
                this.amount = this.bills[this.network][n]['data_amount'];

               
                this.discountAmount = this.bills[this.network][n]['price'] -((this.bonus / 100) * this.bills[this.network][n]['price']);

                this.details = getLastString(this.bills[this.network][n]["id"])+ '-'+ this.bills[this.network][n]["price"];
                
            },
            
            network(n){
                this.plans = this.bills[n];
                //console.log(this.plans);
                this.network_code = this.networks[n];
                this.bonus = this.data[n];

                
            }
            
            },
            created(){
               
            }
            });
</script>