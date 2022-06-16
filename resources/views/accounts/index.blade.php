@extends('layouts.master')
@section('content')
@include('accounts.partials.wizard.cards')
    <div class="row  bg-light">
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Deposit To Your Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Withdraw From Savings
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Send Money
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
            Airtime and Data
        </button>
        <button data-bs-toggle="modal" data-placement="top" data-bs-target="#create_saving" class="btn btn-primary col-sm-12 col-md-2 m-auto">
           Pay for  Utilities
        </button>
    </div>
    @include('accounts.index')
    @include('accounts.partials.modals.suspend_modal')
    @include('accounts.partials.modals.unsuspend_modal')
    @include('accounts.partials.modals.blacklist_modal')
    @include('accounts.partials.modals.unblacklist_modal')
    @include('accounts.partials.modals.loan_limit_modal')
<script>

    $('.btn-acc-actions').on('click',function(e){
        let id = $(this).data('account').val();
        $('input.acount_id').val(id);
    });
</script>

@endsection