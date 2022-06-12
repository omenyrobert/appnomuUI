
        <form action="{{route('airtime.buy')}}" method="POST" class="">
        @csrf
        <div class="row mt-5 card">
            <input id="operator_id" name="operator"type="number" hidden>
            <!-- <input id="rate_id" name="rate_id"type="number" hidden> -->
            <div class="col-sm-12">
                <label>Enter Phone Number</label>
                <input id="phone" type="number" name="phone"class="form-control mt-2" placeholder="Enter valid Phone number" />
            </div>
            <div class="col-sm-12">
                  <label>Airtime Offer</label>
                  <select class="form-control mt-2" id="select_rate_id"name="select_rate_id">
                        <option value="select">select airtime offer</option>
                        @foreach($rates as $rate)
                            <option value="{{$rate->id}}" data-lower="{{$rate->lower_limit}}" data-upper="{{$rate->upper_limit}}">{{$rate->lower_limit.' - '.$rate->upper_limit.' @ '.$rate->bonus.'% bonus'}}</option>
                        @endforeach
                  </select>
            </div>
            <div class="col-sm-12 div-amount" hidden>
                <label>Enter Amount</label>
                <input id="amount_op" type="number" name="amount" class="form-control mt-2" placeholder="Enter valid amount" />
                <div class="invalid-feedback">Enter and amount within range of airtime offer</div>
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