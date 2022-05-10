<div class="p-5 bg-white card">
<form id="student_details" class="row g-3 ">
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
           <select name="student_gender" id="student_gender" class="form-control">
                  <option value="">select Gender</option>
                  <option value="male">Male</option>
                  <option value="Female">Female</option>
           </select>
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


    <div class="col-md-6" >
           <button type="submit" id="btn-nxt-school" class="btn btn-success">Add Student</button>
    </div>
  
</form>
</div>