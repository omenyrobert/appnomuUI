<div class="p-5 bg-white card">
    <h5>Loan Details</h5>    
   
    <div class="col-md-6 form-group">
           <label for="loan_category">Loan Category</label>
           <select name="loan_category" id="loan_category" class="form-control">
                  <option value="">--Select Your Loan Category--</option>
                  @foreach($categories as $category)
                     <option value="{{$category->id}}">{{$category->loan_amount}} Ugx at {{$category->interest_rate}}% interest for {{$category->loan_period}} days in {{$category->installments}} installments</option>
                  @endforeach
           </select>
          
    </div>
</div>