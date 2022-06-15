<form id="form-reset" class="foom"  method="post" action="{{route('user.register')}}" hidden>
        @csrf
          <h4 class="text-black"> Reset Password</h4>
          <br/>
          
          <label>New Password</label>
          <input type="password" class="form-control" name="password" placeholder="Enter Password" required />
          <br/>
          <label>Confirm Password</label>
          <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required/>
          <br/>
          <button class="form-control text-black fw-bold" style="background-color: #ffa500;">Reset </button>
          <br/>
         
</form>