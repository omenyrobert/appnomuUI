<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
<form action="{{route('utility.pay.electricity')}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
    @csrf
            <div class="row mt-5 ">
            <div class="col-sm-12">
                    <label>Select Provider</label>
                    <select class="form-control mt-2" name="biller_id">
                        <option value="select">select provider</option>
                    </select>
                </div>
                <div class="col-sm-12">
                    <label>Select Offer</label>
                    <select class="form-control mt-2" name="rate_id">
                        <option value="select">select Offer</option>
                    </select>
                </div>
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input type="number" name="amount"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <label>Enter Service Account Number</label>
                    <input type="text" name="subscriber_account"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                  <label>Select Purchasing Account</label>
                  <select class="form-control mt-2" name="source">
                  <option value="select">select purchasing Account</option>
                      <option value="savings">Savings Account</option>
                      <option value="loans">Loan Account</option>
                  </select>
            </div>
                <div class="col-sm-12">
                    <br/>
                    <button class="btn btn-primary form-control">Pay Bill</button>
                </div>
            </div>
        </form>
<!-- </div> -->