<!-- <div class="p-5 bg-white card col-sm-12 col-md-6 mx-auto"> -->
        <form action="{{route('make.withdraw')}}" method="POST"class="">
            @csrf
            <div class="row mt-5">
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input style="background-color: #04273d; color: #fff;" type="number" id="amount" name="amount" class="form-control mt-2" placeholder="Enter valid amount" required/>
                </div>
                <div class="col-sm-12">
                      <label>Select Currency </label>
                      <select style="background-color: #04273d; color: #fff;" class="form-control mt-2 single-select" id="currency" name="currency" required>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Withdraw Account</label>
                      <select style="background-color: #04273d; color: #fff;" class="form-control mt-2" id="source" name="source" required>
                          <option value="savings" selected>Savings Account</option>
                          <option value="loans">Loan Account</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Destination Account Type</label>
                      <select style="background-color: #04273d; color: #fff;" class="form-control mt-2" id="type" name="type" required>
                      <option value="select">select destination account type</option>
                          <option value="account">Bank</option>
                          <option value="mobilemoney">Mobile Money</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Destination Account</label>
                      <select style="background-color: #04273d; color: #fff;" class="form-control mt-2" id="destination" name="destination" required>
                          <option value="withdraw">Withdraw to my account</option>
                          <option value="transfer" selected>Withdraw to another account</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Bank or mobile money operator </label>
                      <select style="background-color: #04273d; color: #fff;" class="form-control mt-2 single-select" id="account_bank" name="account_bank" required>
                      </select>
                </div>
                <div class="col-sm-12">
                    <label id="lbl-account-number">Account number Or Phone Number</label>
                    <input style="background-color: #04273d; color: #fff;" type="text" id="account_number" name="account_number" class="form-control"  placeholder="enter  account number or phone number" required>
                </div>
                <div class="col-sm-12">
                    <label >Beneficiary</label>
                    <input style="background-color: #04273d; color: #fff;" type="text" id="beneficiary" name="beneficiary" class="form-control"  placeholder="enter  beneficiary's full names" required>
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button type="submit" class="btn btn-primary form-control">Send Money</button>
                </div>
            </div>
        </form>
<!-- </div> -->