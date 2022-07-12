<form id="form-forgot" class="foom" method="post" action="{{route('user.login')}}" >
        	@csrf
    <label>Email</label>
    <input type="email" id="data_email" name="data"class="form-control" placeholder="Enter Email" required/>
    <br/>
    
    <button id="btn-forgot" type="button" class="form-control text-black fw-bold" style="background-color: #ffa500;">Enter verification Email</button>
    <br/>
    <div class="login-bottom">
        <!-- <h6><input type="checkbox"/> Remember me</h6>&nbsp;&nbsp;&nbsp;<h6 id="forgot"> Forgot Password</h6> -->
        <a type="button" id="btn-register" class="form-control text-black fw-bold" style="background-color: #ffa500;">Register</a>
        <a type="button" id="btn-signin" class="form-control text-black fw-bold" style="background-color: #ffa500;">Sign In</a>
    </div>
    
</form>