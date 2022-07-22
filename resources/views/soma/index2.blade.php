@extends('layouts.master')
@section('content')
          <div style="height: 100vh;">

              <div class="p-5">
                <div class="card-body ">
                <ul class="nav flex-row">
                    
                    <li class="nav-item" style="border: 1px #24a0ed solid; border-radius: 7px; margin: 10px;">
                      <a class="nav-link" href="#">Students</a>
                    </li>
                    <li class="nav-item" style="border: 1px #24a0ed solid; border-radius: 7px; margin: 10px;">
                      <a class="nav-link" href="#">Head Masters</a>
                    </li>
                                        
                    <li class="nav-item" style="border: 1px #24a0ed solid; border-radius: 7px; margin: 10px;">
                      <a class="nav-link" href="{{route('soma.pending')}}">Pending Soma Loans</a>
                    </li>
                    <li class="nav-item" style="border: 1px #24a0ed solid; border-radius: 7px; margin: 10px;">
                      <a class="nav-link" href="{{route('soma.approved')}}">Approved Soma Loans</a>
                    </li>
                    <li class="nav-item" style="border: 1px #24a0ed solid; border-radius: 7px; margin: 10px;">
                      <a class="nav-link" href="{{route('soma.declined')}}">Declined Soma Loans</a>
                    </li>
                    <li class="nav-item" style="border: 1px #24a0ed solid; border-radius: 7px; margin: 10px;">
                      <a class="nav-link" href="{{route('soma.held')}}">Soma Loans On Hold</a>
                    </li>
                    <li class="nav-item" style="border: 1px #24a0ed solid; border-radius: 7px; margin: 10px;">
                      <a class="nav-link" href="{{route('soma.late')}}">Late Soma Loans</a>
                    </li>                
                    
                </ul>
                </div>
                <div class="container p-5" style="background-color: #04273d; color: #fff; border-radius: 20px;">
            <table class="table table-striped table-hover">
              <thead class="text-white">
                <th>Date</th>
                <th>Transaction ID</th>
                    <th>User</th>
     
                <th>Saving Amount</th>
                <th>Interest</th>
               
              </thead>
              <tbody>
                
                 
                <tr class="text-white">
                  <td class="text-white">12-04-2022</td>
                  <td class="text-white">2</td>
                    <td class="text-white">Omeny Robert</td>
                  <td class="text-white">205,000</td>
                  <td class="text-white">10%</td>
                </tr>
                <tr class="text-white">
                  <td class="text-white">12-04-2022</td>
                  <td class="text-white">2</td>
                    <td class="text-white">Omeny Robert</td>
                  <td class="text-white">205,000</td>
                  <td class="text-white">10%</td>
                </tr>
                <tr class="text-white">
                  <td class="text-white">12-04-2022</td>
                  <td class="text-white">2</td>
                    <td class="text-white">Omeny Robert</td>
                  <td class="text-white">205,000</td>
                  <td class="text-white">10%</td>
                </tr>
                <tr class="text-white">
                  <td class="text-white">12-04-2022</td>
                  <td class="text-white">2</td>
                    <td class="text-white">Omeny Robert</td>
                  <td class="text-white">205,000</td>
                  <td class="text-white">10%</td>
                </tr>
                <tr class="text-white">
                  <td class="text-white">12-04-2022</td>
                  <td class="text-white">2</td>
                    <td class="text-white">Omeny Robert</td>
                  <td class="text-white">205,000</td>
                  <td class="text-white">10%</td>
                </tr>
              </tbody>
            </table>

</div>
              </div>
            </div>
            <!-- end col-md-12 -->
      </div>
      @include('soma.partials.scripts') 
@endsection