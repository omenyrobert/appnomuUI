<form action="{{route('account.blacklist')}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
    @csrf
    <div class="row mt-5 ">
    <input id="id_account_blacklist" type="number" name="id"class="form-control mt-2 account_id" hidden />
        
        <div class="col-sm-12">
            <label>Reason For Blacklisting</label>
            <textarea id="reason" name="reason"class="form-control mt-2" placeholder="Enter Reason for Blacklist " rows="5">
            </textarea>
        </div>
       
        <div class="col-sm-12">
            <br/>
            <button type="submit" class="btn btn-primary form-control">Blacklist Account</button>
        </div>
    </div>
</form>