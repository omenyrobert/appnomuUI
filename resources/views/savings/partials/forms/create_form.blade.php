<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
        <form action="{{route('make.deposit')}}" style="background-color: #04273d; color: #fff;" method="post" class="">
            @csrf
            <div class="row mt-5 ">
                <div class="col-sm-12">
                    <label>Select Category</label>
                    <select style="background-color: #04273d; color: #fff;" class="form-control mt-2" name="category">
                        <option value="select">select Category</option>
                    </select>
                </div>
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input style="background-color: #04273d; color: #fff;" type="number" name="amount"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <label>Select Currency</label>
                    <select style="background-color: #04273d; color: #fff;" class="form-control mt-2" name="currency">
                        <option value="select">select Currency to Pay In</option>
                     
                    </select>
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button class="btn btn-primary form-control">Make Saving</button>
                </div>
            </div>
        </form>
<!-- </div> -->