@extends('layouts.master')
@section('content')
    @include('withdraws.partials.wizard.cards')
    <div class="m-4" style="background-color: #113c56; border-radius: 15px;">
    <div class="row p-4">
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn border-primary text-primary font-bold col-sm-12 col-md-2 m-auto">
        <i style="margin-right: 20px;" class="bi bi-credit-card-2-back-fill text-primary"></i> Deposit
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn border-primary text-primary font-bold col-sm-12 col-md-2 m-auto">
        <i style="margin-right: 20px;" class="bi bi-phone-fill text-primary"></i>  Withdraw 
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn border-primary text-primary font-bold col-sm-12 col-md-2 m-auto">
        <i style="margin-right: 20px;" class="bi bi-currency-exchange text-primary"></i>  Send Money
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn border-primary text-primary font-bold col-sm-12 col-md-2 m-auto">
        <i style="margin-right: 2px;" class="bi bi-globe text-primary"></i> Airtime and Data
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn border-primary text-primary font-bold col-sm-12 col-md-2 m-auto">
        <i style="margin-right: 20px;" class="bi bi-droplet-fill text-primary"></i>  Pay for  Utilities
        </button>
    </div>
    @include('withdraws.partials.tables.withdraws_table')
    </div><br/><br/>
</div>
@endsection