<form id="form-signup" class="foom"  method="post" action="{{route('user.register')}}" hidden>
        @csrf
          <h4 class="text-black"> SignUp</h4>
          <br/>
          <label>Full Names</label>
          <input type="text" id="name" class="form-control" name="name" placeholder="Enter your Full Names" required/>
          <div class="invalid-feedback">you must choose a country</div>
          <br/>
          <label>Phone</label>
          <input type="text" id="telephone" class="form-control" name="telephone" placeholder="Enter Phone Number" required/>
          <div class="invalid-feedback">you must choose a country</div>
          <br/>
          <label>Email</label>
          <input type="email" id="email" class="form-control" name="email"placeholder="Enter Email" required/>
          <div class="invalid-feedback">you must choose a country</div>
          <br/>
          <label>Nationality</label>
            <select name="country" id="country" class="form-select" aria-label="Default select example" >
              <option value="select">select Nationality</option>
              @foreach($countries as $country)
                <option value="{{$country->ISO}}">{{$country->name}}</option>
              @endforeach
            </select>
            <div class="invalid-feedback">you must choose a country</div>
          <br/>
          <label>refferer</label>
          <input type="number" id="referrer" class="form-control" name="refferer" placeholder="Enter refferers code" />
          <br/>
          <label>Password</label>
          <input type="password" id="password" class="form-control" name="password" placeholder="Enter Password" required />
          <br/>
          <label>Repeat Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat Password" required/>
          <br/>
          <button id="btn-signup" class="form-control text-black fw-bold" style="background-color: #1d9ff0; color: #fff; border: none; border-radius: 10px">Sign Up </button>
          <br/>
          <div class="login-bottom">
            <h6><input type="checkbox"/> Remember me</h6>&nbsp;&nbsp;&nbsp;
			/* <h6> Forgot Password</h6> */
          </div>
        </form>