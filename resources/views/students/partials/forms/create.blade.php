<form id="student_details" class="row g-3">
<h5>Student Details</h5>
<div class="col-md-6 ">
        <label for="student_fname" class="form-label">First Name</label>
        <input type="text" name="student_fname" class="form-control"id="student_fname" placeholder="students first name" required>
</div>
<div class="col-md-6 ">
       <label for="student_lname" class="form-label">Last Name</label>
       <input type="text" name="student_lname" class="form-control" id="student_lname" placeholder="students last name" required>
</div>


<div class="col-md-6 ">
           <label for="student_lname"class="form-label">Date Of Birth</label>
           <input type="date" class="form-control" name="student_dob" id="student_dob" required>
    </div>
    <div class="col-md-6">
           <h6>select Gender</h6>
           <input type="radio" name="student_gender" class="form-control" id="student_gender" value="Male" >
           <label for="student_gender" class="form-label">Male</label>
           <input type="radio" name="student_gender" class="form-control" id="student_gender" value="Female" >
           <label for="student_gender" class="form-label">Female</label>
    </div>

    <div class="col-md-6 ">
           <label class="form-label" for="student_contact">Student contact</label>
           <input type="phone" class="form-control" name="student_phone" id="student_contact" >
    </div>
    <div class="col-md-6 ">
           <label class="form-label" for="sch_admin_num">School admission number</label>
           <input type="phone" class="form-control" name="sch_admin_num" id="sch_admin_num" >
    </div>
    <div class="col-md-6 ">
           <label class="form-label" for="student_class">Class</label>
           <select class="form-control" name="student_class" id="student_class">
                  <option value="">--Select Class--</option>
                  @foreach($grades as $grade)
                     <option value="{{$grade->id}}">{{$grade->name}}</option>
                  @endforeach
           </select>
    </div>


    <div class="col-md-6" hidden>
           <button type="button" id="btn-nxt-school" class="btn btn-success">Next</button>
    </div>








  
</form>

<div  class="row">
    
   
<div class="col-12">
    <label for="inputAddress" class="form-label">Address</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
  </div>
  <div class="col-12">
    <label for="inputAddress2" class="form-label">Address 2</label>
    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
  </div>
  <div class="col-md-6">
    <label for="inputCity" class="form-label">City</label>
    <input type="text" class="form-control" id="inputCity">
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">State</label>
    <select id="inputState" class="form-select">
      <option selected>Choose...</option>
      <option>...</option>
    </select>
  </div>
  <div class="col-md-2">
    <label for="inputZip" class="form-label">Zip</label>
    <input type="text" class="form-control" id="inputZip">
  </div>
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Check me out
      </label>
    </div>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Sign in</button>
  </div>    
    
   
</div>
