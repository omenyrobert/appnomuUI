<form id="form-login" class="foom" method="post" action="{{route('user.login')}}">
        	@csrf
    <label>Email</label>
    <input type="email" name="email"class="form-control" placeholder="Enter Email" required/>
    <br/>
    <label>Password</label>
    <input type="password" name="password" class="form-control" placeholder="Enter Password" required />
    <br/>
    <button type="submit" class="form-control fw-bold" style="  background-color: #1d9ff0; color: #fff; border: none; border-radius: 10px;">Login </button>
    <br/>
    <div class="login-bottom">
        <h6><input type="checkbox"/> Remember me</h6>&nbsp;&nbsp;&nbsp;<h6 id="forgot"> Forgot Password</h6>
    </div>
    <button type="submit" id="btn-register" class="form-control fw-bold" style="  background-color: #1d9ff0; color: #fff; border: none; border-radius: 10px;">Register</button>
</form>