
<div id="student_details"class=" p-5 bg-white card ">
     <h5>Business Details</h5>
     <input type="number" name="business_id" value={{$business != null ? $business->id : 0}} hidden>
     <div class="col-md-6 ">
             <label for="name" class="form-label">Name</label>
             <input type="text" name="name" class="form-control"id="name" placeholder="name of business" value="{{$business != null ? $business->name: ''}}" required>
     </div>
     <!-- <div class="col-md-6 ">
            <label for="student_lname" class="form-label">Business Owner(enter email if your the new owner)</label>
            <input type="email" name="owner_email" class="form-control" id="owner_email" placeholder="students last name" required>
     </div> -->              
     
     <div class="col-md-6 ">
            <label class="form-label" for="location">Location</label>
            <input type="text" class="form-control" name="location" id="location" placeholder="location of business" value="{{$business != null ? $business->location: ''}}" required>
     </div>
     
     <div class="col-md-6 ">
            <label class="form-label" for="district">District</label>
            <select class="form-control" name="district" id="district">
                   <option value=" ">--Select District--</option>
                   @foreach($districts as $district)
                      <option value="{{$district->id}}">{{$district->name}}</option>
                   @endforeach
            </select>
     </div>
     <div class="col-md-6 ">
            <label class="form-label" for="license">Business License</label>
            <input type="file" name="license" id="license" class="border border-primary" placeholder="upload photo of your business license" class="form-control" >
     </div>
     <div class="col-md-6 ">
            <label class="form-label" for="premises">Premises picture</label>
            <input type="file" name="premises" id="premises" class="border border-primary" placeholder="upload photo of your business premises" class="form-control" >
     </div>
     <div class="col-md-6 ">
            <label class="form-label" for="business_owner_photo">Business photo(with you inside the business)</label>
            <input type="file" name="business_owner_photo" id="business_owner_photo" class="border border-primary" placeholder="upload photo of your business with you inside the business" class="form-control" >
     </div>
     <!-- <div class="col-md-6" >
            <button type="submit" id="btn-nxt-school" class="btn btn-success">Add Student</button>
     </div>     -->
</div>
    
       

