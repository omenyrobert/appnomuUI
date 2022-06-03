<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
        <form action="{{route('airtime.rate.store')}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
            @csrf
            <div class="row mt-5 ">
                <div class="col-sm-12">
                    <label>Lower Limit</label>
                    <input type="number" name="lower_limit"class="form-control mt-2" placeholder="Enter minimum amount for this offer" />
                </div>
                <div class="col-sm-12">
                    <label>Upper Limit</label>
                    <input type="number" name="upper_limit"class="form-control mt-2" placeholder="Enter maximum airtime amount for this offer" />
                </div>

                <div class="col-sm-12">
                    <label>Promotional Bonus</label>
                    <input type="number" name="bonus"class="form-control mt-2" placeholder="Enter bonus in percentage" />
                </div>
               
                <div class="col-sm-12">
                    <br/>
                    <button type="submit" class="btn btn-primary form-control">Create</button>
                </div>
            </div>
        </form>
<!-- </div> -->