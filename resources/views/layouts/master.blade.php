<!DOCTYPE html>
<html>
    <head>
       @include('layouts.partials.head')
    </head>
    <body class="fixed-left">
         <!--wrapper -->
        <div id="wrapper" >
            <div >
                @include('layouts.partials.header')

            </div>
            <div class="row">

                <div class="col-md-2"> 
                <!-- ========== Left Sidebar ========== -->
    
                @include('layouts.partials.sidebar')
                <!-- /Left Sidebar -->
                </div>
                <div class="content-page col-md-10">
                <!-- ============================================================== -->            
                <!-- Start right Content here -->
                <!-- ============================================================== -->
                @yield('content')
                @include('layouts.partials.footer')
            </div>
            </div>
        <!-- /wrapper -->
        @include('layouts.partials.javascripts')
    </body>
</html>