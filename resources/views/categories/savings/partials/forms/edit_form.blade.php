<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
<form action="" method="post"class="my-auto mx-auto border border-1 my-2 modal-forms">
            @csrf
            <div class="row mt-5 ">
                <div class="col-sm-12">
                    <label>Upper Limit</label>
                    <input type="number" name="upper_limit"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <label>Lower Limit</label>
                    <input type="number" name="lower_limit"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <label>Status</label>
                    <select  name="status" class="form-control mt-2" >
                        <option value="Active">Active</option>
                        <option value="InActive">InActive</option>
                    </select>
                </div>
               
                <div class="col-sm-12">
                    <br/>
                    <button class="btn btn-primary form-control">Update</button>
                </div>
            </div>
        </form>
<!-- </div> -->