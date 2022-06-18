<form action="{{route('account.suspend')}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
            @csrf
            <div class="row mt-5 ">
            <input id="id_account_suspend" type="number" name="id" class="form-control mt-2 account_id" hidden />

                <div class="col-sm-12 col-md-6">
                    <label>Duration</label>
                    <select class="form-control mt-2" id="select_duration"name="duration">
                        <option value="days">Days</option>
                        <option value="weeks">Weeks</option>
                        <option value="months">Months</option>
                  </select> 
                </div>
                <div class="col-sm-12 col-md-6">
                    <label>Period</label>
                    <input id="period"type="number" name="period"class="form-control mt-2" placeholder="Enter suspension period" />
                </div>
                <div class="col-sm-12">
                    <label>Reason For Suspension</label>
                    <textarea id="reason" name="reason"class="form-control mt-2" placeholder="Enter Reason for Suspension " rows="5">

                    </textarea>
                </div>

               
                <div class="col-sm-12">
                    <br/>
                    <button type="submit" class="btn btn-primary form-control">Suspend Account</button>
                </div>
            </div>
        </form>