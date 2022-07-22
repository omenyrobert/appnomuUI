
        <form action="{{route('airtime.buy')}}" method="POST" class="">
        @csrf
        <div class="row mt-5">
            <input id="operator_id" name="operator"type="number" hidden>
            <!-- <input id="rate_id" name="rate_id"type="number" hidden> -->
            <div class="col-sm-12">
                <label>Enter Phone Number</label>
                <input style="background-color: #04273d; color: #fff;" id="phone" type="number" name="phone"class="form-control mt-2" placeholder="Enter valid Phone number" />
            </div>
            <div class="col-sm-12">
                  <label>Airtime Offer</label>
                  <select style="background-color: #04273d; color: #fff;" class="form-control mt-2" id="select_rate_id"name="select_rate_id">
                  </select>
            </div>
            <div class="col-sm-12 div-amount" hidden>
                <label>Enter Amount</label>
                <input style="background-color: #04273d; color: #fff;" id="amount_op" type="number" name="amount" class="form-control mt-2" placeholder="Enter valid amount" />
                <div class="invalid-feedback">Enter an amount within range of airtime offer</div>
            </div>
            <div class="col-sm-12">
                  <label>Select Purchasing Account</label>
                  <select style="background-color: #04273d; color: #fff;" class="form-control mt-2" name="account">
                  <option value="select">select purchasing Account</option>
                      <option value="savings"selected>Savings Account</option>
                      <option value="loans">Loan Account</option>
                  </select>
            </div>
            <div class="col-sm-12">
                <br/>
                <button class="btn btn-primary form-control">Buy Airtime</button>
            </div>
        </div>
    </form>
</div>