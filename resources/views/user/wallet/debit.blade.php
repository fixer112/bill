@extends('user.layout')
@section('title','Debit Wallet')
@section('head')
<script src="{{ asset('js/vue.js')}}"></script>
@endsection
@section('content')
<div class="row">
    <div class="col-10 mx-auto card p-5">
        <h4 class="text-6 mb-4">Debit Wallet by Admin</h4>
        <form method="POST" accept="{{url()->current()}}" id="debit">
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
            <div class="form-group">
                <label>With Description</label>
                <div class="input-group">

                    <select name="with_desc" class="form-control @error('with_desc') is-invalid @enderror"
                        v-model="enabled" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('with_desc')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label>Profit</label>
                <div class="input-group">
                    <input name="profit" type="number" class="form-control @error('profit') is-invalid @enderror" />
                    @error('profit')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group" v-if="enabled == '1'">
                <label>Description</label>
                <div class="input-group">

                    <textarea name="desc" class="form-control @error('desc') is-invalid @enderror"
                        required>Charges for previous transaction(s)</textarea>
                    @error('desc')
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
<script>
    new Vue({
    el: '#debit',
    data: function() {
    return {
        enabled:'1',

    }
  }, 
       methods:{
        },
        watch:{
           
            
            },
            created(){

            }
            });
</script>
@endsection