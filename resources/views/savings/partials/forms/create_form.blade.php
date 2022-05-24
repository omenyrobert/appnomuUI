<!-- <div class="p-5 bg-white  col-sm-12 col-md-6 mx-auto"> -->
        <form action="" method="post" class="my-auto mx-auto border border-1 my-2 modal-forms">
            <div class="row mt-5 ">
                <div class="col-sm-12">
                    <label>Select Category</label>
                    <select class="form-control mt-2" name="category">
                        <option value="select">select Category</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">UGX {{$category->savingCategory->lowerlimit}} - UGX {{$category->savingCategory->upperlimit}}  for {{$category->Saving_Period}} days at {{$category->Interest}} %</option>

                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12">
                    <label>Enter Amount</label>
                    <input type="number" name="amount"class="form-control mt-2" placeholder="Enter valid amount" />
                </div>
                <div class="col-sm-12">
                    <br/>
                    <button class="btn btn-primary form-control">Make Saving</button>
                </div>
            </div>
        </form>
<!-- </div> -->