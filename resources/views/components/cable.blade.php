<script src="{{ asset('js/vue.js')}}"></script>

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

            <div class="form-group" v-if="plans">
                <label>Plan</label>
                <select class="custom-select @error('plan') is-invalid @enderror" required v-model="plan">
                    <option value="">Select Plan</option>
                    <template v-for="p,key in plans">
                        <option :value="key">@{{p['name'].toUpperCase()}} - @{{p['price']}} -
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
            <input name="amount" readonly required hidden />

            <div class="form-group">
                <label>Discount Amount at @{{bonus}}%</label>
                <div class="input-group">
                    <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                    </div>

                    <input class="form-control" type="number" name="discount_amount" required v-model="discountAmount"
                        readonly>

                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Smart Card Number</label>
                <div class="input-group">
                    <input class="form-control @error('smart_no') is-invalid @enderror" name="smart_no"
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
                    <input class="form-control" placeholder="Smart card name" required v-model="name" required>

                </div>
            </div>


        </div>
        <button class="btn btn-primary btn-block" type="submit">Continue</button>
    </div>
</div>

<script>
    var airtime = new Vue({
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
        bills:@json(config("settings.bills.cable")),
        bonus:0,
        
        
    }
  }, 
       methods:{
         

        },
        watch:{

            type(n){
                //console.log(n);
                this.plans = this.bills[n];
                this.bonus = this.data[n];
                
                //console.log(this.plans);
            },
            plan(n){
                this.amount = this.bills[this.type][n]['amount'];
                this.discountAmount = this.bills[this.type][n]['price'] - ((this.bonus / 100) * this.bills[this.type][n]['price']);
            }
            
           
            
            },
            created(){
                //console.log(this.bills);
            }
            });

</script>