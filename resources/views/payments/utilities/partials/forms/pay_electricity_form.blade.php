<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
<form action="{{route('utility.pay.electricity')}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
    @csrf
            <div class="row mt-5 ">
                <div class="col-sm-12">
                    <label>Select Offer</label>
                    <select class="form-control mt-2" name="category">
                        <option value="select">select Offer</option>
                        @foreach($e_rates as $e_rate)
                            <option value="{{$e_rate->id}}">UGX {{$e_rate->lower_limit}} - UGX {{$e_rate->upper_limit}}  for {{$e_rate->bonus}}% bonus</option>

                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input type="number" name="amount"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button class="btn btn-primary form-control">Pay Bill</button>
                </div>
            </div>
        </form>
<!-- </div> -->