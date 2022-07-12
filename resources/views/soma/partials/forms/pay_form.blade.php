<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
        <form action="{{route('pay.installment')}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
            @csrf
            <div class="row mt-5 ">
               <input id="repay_id" name="id" type="number" hidden>
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input id="repay_amount"type="number" name="amount"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <label>Select Currency</label>
                    <select class="form-control mt-2" name="currency">
                        <option value="select">select Currency to Pay In</option>
                        @foreach($countries as $country)
                            <option value="{{$country->currency_code}}">{{$country->currency_code}}</option>

                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button class="btn btn-primary form-control">Pay Installment</button>
                </div>
            </div>
        </form>
<!-- </div> -->