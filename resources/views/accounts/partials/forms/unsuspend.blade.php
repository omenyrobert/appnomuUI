<form action="{{route('account.status',['action'=>'unsuspend'])}}" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
    @csrf
    <div class="row mt-5 ">
    <input id="id_account_unsuspend" type="number" name="id"class="form-control mt-2 account_id" hidden />
        
        <div class="col-sm-12">
            <label>Reason For Unsuspending Account</label>
            <textarea id="reason" name="reason"class="form-control mt-2" placeholder="Enter Reason for Unsuspending account " rows="5">
            </textarea>
        </div>
       
        <div class="col-sm-12">
            <br/>
            <button type="submit" class="btn btn-primary form-control">UnSuspend Account</button>
        </div>
    </div>
</form>