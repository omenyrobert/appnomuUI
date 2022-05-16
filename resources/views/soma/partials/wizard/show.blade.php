<div class="row  bg-light ">
    <div class="col-md-6 col-sm-12 card" style="background-color: white;">
        <h5>Student Details</h5>
        <div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>Name:</span>
                <span style="margin-left:25px ; text-align:end;">{{$student->fname}} {{$student->lname}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>Age:</span>
                <span>{{$student->dob}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>Class</span>
                <span>{{$student->class_name}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>Gender</span>
                <span>{{$student->gender}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>School Admission Number</span>
                <span>{{$student->sch_admission_num}} </span>
            </div>
            @if($student->phone)
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>Contact</span>
                <span>{{$student->phone}} </span>
            </div>
            @endif
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <a href="">
                    <div>view more student details</div>
                    <div>v </div>
                </a>
            </div>
            
        </div>
    </div>
    <div class="col-md-6 col-sm-12 card" style="background-color: white;">
        <h5>School Details</h5>
        <div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>Name:</span>
                <span style="margin-left:25px ; text-align:end;">{{$school->school_name}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>HeadTeacher:</span>
                <span>{{$school->fname}} {{$school->fname}}</span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>HeadTeacher's Contact</span>
                <span>{{$school->phone}} </span>
            </div>
            <div style="border-bottom: thin solid black; padding:20px; bg-primary">
                <span>School Location</span>
                <span>{{$school->district->name}}  </span>
            </div>
           
            
        </div>
    </div>
</div>