<!DOCTYPE html>
<html dir="ltr" lang="en">
  
<!-- Mirrored from demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/html/material/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 24 Apr 2022 10:57:52 GMT -->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta
      name="keywords"
      content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, material admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, material design, material dashboard bootstrap 5 dashboard template"
    />
    <meta
      name="description"
      content="MaterialPro is powerful and clean admin dashboard template, inpired from Google's Material Design"
    />
    <meta name="robots" content="noindex,nofollow" />
    <title>Appnomu Savings and loans</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/materialpro/"/>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/images/favicon.png"/>
    <link rel="stylesheet" href="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/libs/apexcharts/dist/apexcharts.css"/>
    <!-- Custom CSS -->
    <link href="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/css/style.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   @include('auth.styles')
  </head>

  <body>
    <div class="preloader" style="background-color: #ffa500;">
      <center>
        <img src="logo.png" style="width: 250px; margin-top: 50vh;"/>
      </center>
    </div>
    <div class="row bg-white">
      <div class="col-md-6 left-div">
        <br/><br/>
       <div class="caption-div">
        <h1 class="text-white fw-bold">Get  Loans From UGX.15,000 to UGX. 15,000,000 Instantly On Your Smartphone</h1>
        <h4 class="text-white fw-bold ">Soma Loan, Business Loan etc</h4>
        <div class="cap-down">
          <h6 class="text-black"> Pay Bills</h6>
          <h6 class="text-black">Buy Airtime & data</h6>
          <h6 class="text-black">Transfer money</h6>

        </div>
       </div>
      </div>
      <div class="col-md-6 login-left" >
      <div id="div-signin"  class="login-div">
        <h4 class="text-black signin-text"> SignIn Now To Get Yourself A Loan</h4>
        <br/>
        @include('auth.partials.forms.signin')
      </div>
      <br>
      <br>
      <br>
	  <div id="div-signup" class="login-div" hidden>
        <h4 class="text-black signin-text"> SignUp Now To Get Yourself A Loan</h4>
        <br/>
          @include('auth.partials.forms.signup')
	  </div>
	 
	  @include('auth.partials.modals.forgot_modal')
	  @include('auth.partials.modals.email_modal')
	  @include('auth.partials.modals.sms_modal')
	  @include('auth.partials.modals.reset_password_modal')
         

      </div>
      </div>
    </div>
    <div class="chat-windows"></div>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- apps -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/app.min.js"></script>
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/app.init.js"></script>
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/app-style-switcher.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/feather.min.js"></script>
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <!-- Chart JS -->
    <script src="https://demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/dist/js/pages/dashboards/dashboard1.js"></script>
    <script>
      $('#btn-register').on('click',function(e){
      		console.log('register clicked');
        	$('#form-signup').prop('hidden',false);
		    $('#div-signup').prop('hidden',false);
		    $('#div-signin').prop('hidden',true);
		    $('#form-login').prop('hidden',true);
    });

	$('#btn-signup').on('click',function(e){
		e.preventDefault();
    	console.log('sign up clicked')
		let name = $('#name').val();
		let email = $('#email').val();
		let telephone = $('#telephone').val();
		let country = $('#country :selected').val();
		let referrer = $('#referrer').val();
		let password = $('#password').val();
		let password_confirmation = $('#password_confirmation').val();
		$.ajax({
			type:'POST',
			url: "{{route('user.register')}}",
			data: {
				name,
				telephone,
				email,
				country,
				referrer,
				password,
				password_confirmation,
				 _token: '{{csrf_token()}}',
				 forgot:false
			},
			success: function(response){
				console.log(response);
         		$('#email_modal').modal('show');
				$('#email-email').val(response.email);
         		$('#email-token').val(response.token);
			}
		});
		
      });
  
      $('#forgot').on('click',function(e){
      console.log('forgot clicked')
	  $('#form-signup').prop('hidden',true);
		$('#div-signup').prop('hidden',true);
		$('#form-login').prop('hidden',true);
		$('#div-signin').prop('hidden',true);
		$('#div-forgot').prop('hidden',false);
        $('#form-forgot').prop('hidden',false);
		
      });

	  
  
      $('#btn-forgot').on('click',function(e){
        let data = $('#data_email').val();
		console.log('email',data);
        $.ajax({
          type:'POST',
          url: '/forgot',
          data:{ 'forgot':true,data, _token: "{{csrf_token()}}"},
          success: function(response){
			  console.log(response);
            	if(response.status == 'success'){
            		alert('a token has been sent to your email.');
					    $('#div-forgot').prop('hidden',true);
            		$('#form-forgot').prop('hidden',true);
					$('#div-email').prop('hidden',false);
            		$('#verify-email').prop('hidden',false);
            		$('#email-email').val(response.email);
            		$('#email-token').val(response.token);
            	}
          }
        });
	});
  
        $('#btn-verify-email').on('click',function(e){
		console.log('verify email token btn clicked');	
        let code = $('#e_token').val();
        let token = $('#email-token').val();
		let email = $('#email-email').val();
        if(code == token){
          $.ajax({
            type:'POST',
            url: "{{route('verify.email')}}",
            data:{  email,code, _token : '{{csrf_token()}}'},
            success: function(response){
				console.log(response);
              if(response.status == 'success'){
                alert('an sms  token has been sent to your phone.');
				$('#email_modal').modal('toggle');
				$('#sms_modal').modal('show');
            	$('#sms-email').val(response.email);
            	$('#sms_token').val(response.token);
              }
            }
          });
        }
        $('#verify-email-token').addClass('is-invalid');      
      });

	  $('#btn-sms').on('click',function(e){
		  e.preventDefault();
        let sms_token = $('#sms-token').val();
        let token = $('#sms-token').val();
		let email = $('#sms-email').val();
        if(sms_token == token){
          $.ajax({
            type:'POST',
            url: `/verify-phone`,
            data:{ email, sms_token,  '_token' : '{{csrf_token()}}'},
            success: function(response){
              if(response.status == 'success'){
					$('#sms_modal').modal('toggle');
					if(response.forgot == true){						
						$('#reset_password').modal('show');
						$('#email-reset').val(response.email);
				  	}else{
						$('#div-signin').prop('hidden',false);
						$('#form-login').prop('hidden',false);
						$('#div-signup').prop('hidden',true);
						$('#form-signup').prop('hidden',true);
				    }
              	}
            }
          });
        }
        $('#sms_token').addClass('is-invalid');      
      });
  
    </script>
  </body>

</html>
