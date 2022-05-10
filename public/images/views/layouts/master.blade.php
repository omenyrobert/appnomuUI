<!DOCTYPE html>
<html dir="ltr" lang="en">
  
<!-- Mirrored from demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/html/material/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 24 Apr 2022 10:57:52 GMT -->
@include('layouts.partials.head')

  <body>
  <?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
                $user = User::find(Auth::id()); 
             ?>
    <div id="main-wrapper">
     @include('layouts.partials.header')
     @include('layouts.partials.sidebar')
    
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="col-md-5 col-12 bg-white align-self-center">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
              <a href="javascript:void(0)">Home</a>
            </li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
          @yield('content')
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        
        
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- customizer Panel -->
    <!-- ============================================================== -->
   
    <div class="chat-windows"></div>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    @include('layouts.partials.javascripts')
</body>

<!-- Mirrored from demos.wrappixel.com/premium-admin-templates/bootstrap/materialpro-bootstrap/package/html/material/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 24 Apr 2022 10:57:52 GMT -->
</html>
