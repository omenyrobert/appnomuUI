<form id="veriy-email" class="foom" method="post" action="{{route('user.login')}}" >
        	@csrf
    <h4>Email Verification Token</h4>
    <label>Token</label>
    <input type="number" id="e_token"  name="token"class="form-control" placeholder="Enter Email Token" required/>
    <div class="invalid-feedback">enter correct oken</div>

    <input type="email" id="email-email" name="email"class="form-control" placeholder="Enter Email Token" hidden/>
    <input type="number" id="email-token" name="email-token"class="form-control" placeholder="Enter Email Token" hidden/>
    <br/>
    
    <button type="button" id="btn-verify-email" class="form-control text-black fw-bold" style="background-color: #ffa500;">Enter </button>
    <br/>
    <!-- <div class="login-bottom">
         <h6><input type="checkbox"/> Remember me</h6>&nbsp;&nbsp;&nbsp;<h6 id="forgot"> Forgot Password</h6> -->
        <!-- <a type="button" id="btn-register" class="form-control text-black fw-bold" style="background-color: #ffa500;">Register</a>
        <a type="button" id="btn-signin" class="form-control text-black fw-bold" style="background-color: #ffa500;">Sign In</a>
    </div> -->
    
</form>