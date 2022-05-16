<div class="p-5 bg-white card">
    <h5>Loan Details</h5>    
   
    <div class="col-md-6 form-group">
           <label for="student_class">Loan Category</label>
           <select name="loan_category" id="student_class" class="form-control">
                  <option value="">--Select Your Loan Category--</option>
                  @foreach($categories as $category)
                     <option value="{{$category->id}}">{{$category->loan_amount}} @{{$category->interest_rate}} for {{$category->loan_period}} in {{$category->installments}}s</option>
                  @endforeach
           </select>
           <div >
        
    </div>
    </div>
</div>