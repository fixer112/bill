<div class="resp-tab-content resp-tab-content-active" aria-labelledby="tab_item-1" style="display: block;">
    <h4 class="text-6 mb-4">Data Subscription</h4>
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
            <label for="operator">Plan</label>
            <select class="custom-select" id="operator" required="">
                <option value="">Select Plan</option>
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
            <label for="amount">Cost</label>
            <div class="input-group">
                <div class="input-group-prepend"> <span class="input-group-text">$</span>
                </div>

                <input class="form-control" id="amount" placeholder="Enter Amount" disabled="" type="text">
            </div>
        </div>

        <button class="btn btn-primary btn-block" type="submit">Continue</button>
    </form>
</div>