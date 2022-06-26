<style>
</style>

<div style="background-color: #113c56;" class="card col-sm-12 col-md-11 p-5 m-3">
        <form action="" method="POST"class="">
            @csrf
           <h4 class="text-primary">Make A withDraw</h4>
            <div class="mt-5">
                <div class="row">
                    <div class="col-md-4">
                      <label class="text-white">Enter Amount</label>
                    </div>
                    <div class="col-md-8">
                      <input type="number" id="amount" name="amount" style="background-color: #113c56;"  class="form-control" placeholder="Enter valid amount" required/>
                    </div>
                </div>
                <div class="row mt-5">
                  <div class="col-md-4">
                      <label class="text-white">Select Currency </label>
                  </div>    
                  <div class="col-md-8">
                      <select class="form-control single-select" style="background-color: #113c56; color: #fff;"  id="currency" name=" currency" required>
                      <option value="select">select currency to withdraw</option>
                      <option value=""></option>
                            
                                <option value=""></option>
                            
                      
                      </select>
                    </div>
                </div>
                <div class="row mt-5">
                <div class="col-md-4">
                      <label class="text-white">Select Withdraw Account</label>
                </div>
                <div class="col-md-8">
                      <select class="form-control" id="source" style="background-color: #113c56; color: #fff;" name="source" required>
                      <option value="select">select Account</option>
                          <option value="savings">Savings Account</option>
                          <option value="loans">Loan Account</option>
                      </select>
                </div>      
                </div>
                <div class="col-sm-12">
                      <label>Select Destination Account Type</label>
                      <select class="form-control" id="type" name="type" required>
                      <option value="select">select destination account type</option>
                          <option value="account">Bank</option>
                          <option value="mobilemoney">Mobile Money</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Destination </label>
                      <select class="form-control" id="destination" name="destination" required>
                      <option value="select">select destination</option>
                          <option value="withdraw">Withdraw to my account</option>
                          <option value="transfer">Withdraw to another account</option>
                      </select>
                </div>
                <div class="col-sm-12">
                      <label>Select Bank or mobile money operator </label>
                      <select class="form-control single-select" id="account_bank" name="account_bank" required>
                      <option value="select">select Bank or mobileMoney operator</option>
                                <option value=""></option>
                            <option value=""></option>
                      </select>
                </div>
                <div class="col-sm-12">
                    <label id="lbl-account-number">Account number Or Phone Number</label>
                    <input type="text" id="account_number" name="account_number" class="form-control"  placeholder="enter  account number or phone number" required>
                </div>
                <div class="col-sm-12">
                    <label >Beneficiary</label>
                    <input type="text" id="beneficiary" name="beneficiary" class="form-control"  placeholder="enter  beneficiary's full names" required>
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button type="submit" class="btn btn-primary form-control">Make Withdraw</button>
                </div>
            </div>
        </form>
</div>
