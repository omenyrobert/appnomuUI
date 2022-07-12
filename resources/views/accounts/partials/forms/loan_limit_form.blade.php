<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
        <form action="{{route('account.change.limit')}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
            @csrf
            <div class="row mt-5 ">
            <input id="id_account_limit" type="number" name="id"class="form-control mt-2 account_id" hidden />

                <!-- <div class="col-sm-12">
                    <label>Current Limit</label>
                    <input id="current_limit" type="number" name="current_limit"class="form-control mt-2" disabled />
                </div> -->
                <div class="col-sm-12">
                    <label>New Loan Limit</label>
                    <input id="new_limit"type="number" name="new_limit"class="form-control mt-2" placeholder="Enter maximum airtime amount for this offer" />
                </div>

               
                <div class="col-sm-12">
                    <br/>
                    <button type="submit" class="btn btn-primary form-control">Update Loan Limit</button>
                </div>
            </div>
        </form>
<!-- </div> -->