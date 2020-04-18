<div class="resp-tab-content resp-tab-content-active" style="display:block" aria-labelledby="tab_item-0">
    <h4 class="text-6 mb-4">Airtime Recharge </h4>
    <form id="recharge-bill" method="post">

        <div class="form-group">
            <label for="mobileNumber">Mobile Number</label>
            <input type="text" class="form-control" data-bv-field="number" id="mobileNumber" required=""
                placeholder="Enter Mobile Number">
        </div>
        <div class="form-group">
            <label for="operator">Your Operator</label>
            <select class="custom-select" id="operator" required="">
                <option value="">Select Your Operator</option>
                <option>1st Operator</option>
                <option>2nd Operator</option>
                <option>3rd Operator</option>
                <option>4th Operator</option>
                <option>5th Operator</option>
                <option>6th Operator</option>
                <option>7th Operator</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Custom Amount</label>
            <div class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text">{{currencySymbol()}}</span>
                </div>

                <input class="form-control" id="amount" placeholder="Enter Amount" required="" type="text">
            </div>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Continue</button>
    </form>
</div>