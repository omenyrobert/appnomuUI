
        <form action="{{route('airtime.buy')}}" class="">
            @csrf
            <div class="row mt-5 card">
                <input id="operator" name="operator"type="number" hidden>
                <input id="rate_id" name="rate_id"type="number" hidden>
                <div class="col-sm-12">
                    <label>Enter Phone Number</label>
                    <input id="phone" type="number" name="phone"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input id="amount" type="number" name="amount" class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                      <label>Select Purchasing Account</label>
                      <select class="form-control mt-2" name="account">
                      <option value="select">select purchasing Account</option>
                          <option value="savings">Savings Account</option>
                          <option value="loans">Loan Account</option>
                      </select>
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button class="btn btn-primary form-control">Buy Airtime Offer</button>
                </div>
            </div>
        </form>
</div>